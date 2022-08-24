<?php

namespace App\Console\Commands;

use App\Common\CSVReader;
use App\Models\NewSymbol;
use App\Models\Symbol;
use App\Models\SymbolAnalyzed;
use App\Models\SymbolPrice;
use App\Repositories\IDayOffRepository;
use App\Repositories\ProcessHistoryRepository;
use App\Repositories\SymbolAnalyzedRepository;
use App\Repositories\SymbolPriceRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Sunra\PhpSimple\HtmlDomParser;

class AnalyzedCommandCophieu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cophieu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get symbol has volume small';


    protected $repo;

    protected $reader;

    private $analyzedRepository;

    private $processHistoryRepo;

    /** @var IDayOffRepository */
    private $dayOffRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SymbolPriceRepository $repo, SymbolAnalyzedRepository $analyzedRepository, ProcessHistoryRepository $processHistoryRepository, CSVReader $reader, IDayOffRepository $dayOffRepository)
    {
        $this->repo = $repo;
        $this->reader = $reader;
        $this->analyzedRepository = $analyzedRepository;
        $this->processHistoryRepo = $processHistoryRepository;
        $this->dayOffRepository=$dayOffRepository;
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $processDate =(new \DateTime())->format("Y-m-d");
        try {

            if ($this->processHistoryRepo->hasProcess()) {
                return;
            }

            $weekday = (new \DateTime())->format("w");
            $sunday = 0;
            $saturday = 6;
            if ($weekday == $sunday || $weekday == $saturday) {
                return;
            }

            if($this->dayOffRepository->findByDay($processDate)->count()>0){
                return;
            }

            $symbols = NewSymbol::all();

            $this->analyzedRepository->clear();


            $stockOfDay = $this->reader->readFile(fopen(public_path("file.csv"), 'r'));


            foreach ($symbols as $symbol) {

                Log::info("$symbol");

                if (!isset($stockOfDay[$symbol->name])) {
                    Log::error("Error " . $symbol->name);
                    continue;
                }

                $currentVolume = $stockOfDay[$symbol->name]['volume'];

                $lastFiveDay = $this->processHistoryRepo->getLastFiveDay();
                
                $lastFiftyDay = $this->processHistoryRepo->getLastFiftyDay();

                $yesterDay = $this->processHistoryRepo->getYesterday();
                $lastTwoDay = $this->processHistoryRepo->getLastTwoDay();

                $averageFifteenDay = $this->repo->getAverageFifteenDay($symbol,$lastFiftyDay);
                $averageFiveDay = $this->repo->getAverageFifteenDay($symbol,$lastFiveDay);

                $priceYesterday = $this->repo->getValueByDate($symbol, $yesterDay)->price;
                $lowYesterday = $this->repo->getValueByDate($symbol, $yesterDay)->low;
                $highYesterday = $this->repo->getValueByDate($symbol, $yesterDay)->high;
                $priceLastTwoDay = $this->repo->getValueByDate($symbol, $lastTwoDay)->price;
                $lowLastTwoDay = $this->repo->getValueByDate($symbol, $lastTwoDay)->low;
                $highLastTwoDay = $this->repo->getValueByDate($symbol, $lastTwoDay)->high;
                $currentPrice = $stockOfDay[$symbol->name]['close'];
                $currentLowPrice = $stockOfDay[$symbol->name]['low'];
                $currentHighPrice = $stockOfDay[$symbol->name]['high'];

                $sietNenParam = [
                    [
                        "low" => $lowLastTwoDay,
                        'high' => $highLastTwoDay
                    ],
                    [
                        "low" => $lowYesterday,
                        "high" => $highYesterday
                    ],
                    [
                        "low" => $currentLowPrice,
                        "high" => $currentHighPrice
                    ]
                ];

                $rate = $currentVolume ==0 ?0 : $averageFifteenDay / $currentVolume;
                $rate_five_day = $currentVolume ==0 ?0 : $averageFiveDay / $currentVolume;

                Log::info(sprintf("current volume=%s, average =%s", $currentVolume, $averageFifteenDay));


                if (
                       $rate >= 1.1 && $averageFifteenDay >= 10000 && $rate_five_day >= 5
                    // siet nen
                   // && $this->compare([$priceYesterday, $priceLastTwoDay], $currentPrice)
                   // && $this->sietNen($sietNenParam)
                ) {
                    //insert to table analyzed to more analyzed
                    $symbolNeedToInvest = new SymbolAnalyzed();
                    $symbolNeedToInvest->symbol = $symbol->name;
                    $symbolNeedToInvest->volume = $currentVolume;
                    $symbolNeedToInvest->volume_average = $averageFifteenDay;
                    $symbolNeedToInvest->save();
                }

                //insert into symbol price
                $symbolPrice = new SymbolPrice();
                $symbolPrice->date = $processDate;
//                $symbolPrice->date = '2021-01-29';
                $symbolPrice->price = $stockOfDay[$symbol->name]['close'];
                $symbolPrice->low = $stockOfDay[$symbol->name]['low'];
                $symbolPrice->high = $stockOfDay[$symbol->name]['high'];
                $symbolPrice->volume = $stockOfDay[$symbol->name]['volume'];
                $symbolPrice->symbol = $symbol->name;
                $symbolPrice->save();

                Log::info("-------------------------End-------------------------------");
            }

            $this->processHistoryRepo->create([
                "date" => $processDate
//                "date" => '2021-01-29'
            ]);

            echo "done";

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

    }


    /** siet nen vol can kiet
     * @param array $data
     * @param $value
     * @return bool
     */
    private function compare(array $data, $value)
    {
        foreach ($data as $item) {
            if(!$this->match($item, $value)){
                return  false;
            }
        }
        return true;
    }

    private function sietNen(array $values)
    {
        foreach ($values as $value) {
            if (!$this->match($value['high'], $value['low'])) {
                return false;
            }
        }

        return true;
    }

    private function match(float $a, float $b):bool
    {
        if ((int)$b == 0) {
            return  false;
        }
        $result = abs((float)$a - (float)$b) / $b;
        if ($result >= 0.015) {
            return false;
        }
        return true;
    }
}
