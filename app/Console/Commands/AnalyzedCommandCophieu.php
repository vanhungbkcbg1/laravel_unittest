<?php

namespace App\Console\Commands;

use App\Common\CSVReader;
use App\Models\Symbol;
use App\Models\SymbolAnalyzed;
use App\Models\SymbolPrice;
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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SymbolPriceRepository $repo, SymbolAnalyzedRepository $analyzedRepository, ProcessHistoryRepository $processHistoryRepository, CSVReader $reader)
    {
        $this->repo = $repo;
        $this->reader = $reader;
        $this->analyzedRepository = $analyzedRepository;
        $this->processHistoryRepo = $processHistoryRepository;
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

            $symbols = Symbol::all();

            $this->analyzedRepository->clear();


            $stockOfDay = $this->reader->readFile(fopen(public_path("file.csv"), 'r'));

            foreach ($symbols as $symbol) {

                Log::info("$symbol");

                if (!isset($stockOfDay[$symbol->name])) {
                    Log::error("Error " . $symbol->name);
                    continue;
                }

                $currentVolume = $stockOfDay[$symbol->name]['volume'];

                $averageFifteenDay = $this->repo->getAverageFifteenDay($symbol);

                $rate = $currentVolume ==0 ?0 : $averageFifteenDay / $currentVolume;

                Log::info(sprintf("current volume=%s, average =%s", $currentVolume, $averageFifteenDay));


                if ($rate >= 1.5 && $averageFifteenDay >= 100000) {
                    //insert to table analyzed to more analyzed
                    $symbolNeedToInvest = new SymbolAnalyzed();
                    $symbolNeedToInvest->symbol = $symbol->name;
                    $symbolNeedToInvest->volume = $currentVolume;
                    $symbolNeedToInvest->volume_average = $averageFifteenDay;
                    $symbolNeedToInvest->save();
                }

                //insert into symbol price
                $symbolPrice = new SymbolPrice();
//                $symbolPrice->date = (new \DateTime())->format("Y-m-d");
                $symbolPrice->date = '2021-01-29';
                $symbolPrice->price = $stockOfDay[$symbol->name]['close'];
                $symbolPrice->volume = $stockOfDay[$symbol->name]['volume'];
                $symbolPrice->symbol = $symbol->name;
                $symbolPrice->save();

                Log::info("-------------------------End-------------------------------");
            }

            $this->processHistoryRepo->create([
//                "date" => (new \DateTime())->format("Y-m-d")
                "date" => '2021-01-29'
            ]);

            echo "done";

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

    }
}
