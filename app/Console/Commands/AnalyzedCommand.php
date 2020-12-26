<?php

namespace App\Console\Commands;

use App\Models\Symbol;
use App\Models\SymbolAnalyzed;
use App\Models\SymbolPrice;
use App\Repositories\SymbolPriceRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;

class AnalyzedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:analyzed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get symbol has volume small';


    protected $repo;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SymbolPriceRepository $repo)
    {
        $this->repo =$repo;
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
            $token = "5fa56a56a0ffa6f258bc0d959c878d8a";

            $queryString = http_build_query([
                'access_key' => $token,
                "limit" => 50
            ]);

            $symbols = Symbol::all();

            //if failed->get log to get stock failed
//            $symbols = Symbol::where([
//                "name"=>"HT1.XSTC"
//            ])->get();
            foreach ($symbols as $symbol) {

                Log::info("$symbol");

                $url = sprintf("http://api.marketstack.com/v1/tickers/%s/eod/latest", $symbol->name);
                $url = sprintf('%s?%s', $url, $queryString);

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $json = curl_exec($ch);
                curl_close($ch);

                $apiResult = json_decode($json, true);

                if(!isset($apiResult['volume'])){
                    Log::error("Error". $symbol->name);
                    continue;
                }

                $currentVolume =$apiResult['volume'];

                $averageFifteenDay= $this->repo->getAverageFifteenDay($symbol);

                if($currentVolume < $averageFifteenDay){
                    //insert to table analyzed to more analyzed
                    $symbolNeedToInvest =new SymbolAnalyzed();
                    $symbolNeedToInvest->symbol = $symbol->name;
                    $symbolNeedToInvest->volume =$currentVolume;
                    $symbolNeedToInvest->average_volume =$averageFifteenDay;
                    $symbolNeedToInvest->save();
                }

                Log::info("-------------------------End-------------------------------");
            }

            echo "done";

        }catch (\Exception $e){
            Log::error($e->getMessage());
        }

    }
}
