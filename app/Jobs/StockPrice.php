<?php

namespace App\Jobs;

use App\Common\CSVReader;
use App\Events\StockAnalyzed;
use App\Models\Symbol;
use App\Models\SymbolAnalyzed;
use App\Models\SymbolPrice;
use App\Repositories\ProcessHistoryRepository;
use App\Repositories\SymbolAnalyzedRepository;
use App\Repositories\SymbolPriceRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StockPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $repo;

    protected $reader;

    private $analyzedRepository;

    private $processHistoryRepo;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {


    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SymbolPriceRepository $repo, SymbolAnalyzedRepository $analyzedRepository, ProcessHistoryRepository $processHistoryRepository, CSVReader $reader)
    {
        $this->repo = $repo;
        $this->reader = $reader;
        $this->analyzedRepository = $analyzedRepository;
        $this->processHistoryRepo = $processHistoryRepository;
        //

        Log::info("done");


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
            $symbolPrice->date = (new \DateTime())->format("Y-m-d");
//                $symbolPrice->date = '2021-01-29';
            $symbolPrice->price = $stockOfDay[$symbol->name]['close'];
            $symbolPrice->volume = $stockOfDay[$symbol->name]['volume'];
            $symbolPrice->symbol = $symbol->name;
            $symbolPrice->save();

            Log::info("-------------------------End-------------------------------");
        }

        $this->processHistoryRepo->create([
            "date" => (new \DateTime())->format("Y-m-d")
//                "date" => '2021-01-29'
        ]);

        //dispatch event
        event(new StockAnalyzed('Someone'));
    }
}
