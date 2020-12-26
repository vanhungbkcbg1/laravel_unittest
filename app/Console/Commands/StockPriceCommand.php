<?php

namespace App\Console\Commands;

use App\Models\Symbol;
use App\Models\SymbolPrice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;

class StockPriceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Stock';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
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
            $token = "0bf19074694728dec585d8bff5361593";

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

                $url = sprintf("http://api.marketstack.com/v1/tickers/%s/eod", $symbol->name);
                $url = sprintf('%s?%s', $url, $queryString);

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $json = curl_exec($ch);
                curl_close($ch);

                $apiResult = json_decode($json, true);

                $data = [];

                if(isset($apiResult['data']['eod'])){
                    foreach ($apiResult['data']['eod'] as $item) {
                        $date = new \DateTime($item['date']);

                        $data[] = [
                            "date" => $date,
                            "symbol" => $item['symbol'],
                            "price" => $item['close'],
                            "volume" => $item['volume'],
                        ];

                    }
                } else {
                    Log::error("StockError ".$symbol->name);
                }

                if($data){

                    SymbolPrice::insert($data);
                }


                usleep(1000);

                Log::info("-------------------------End-------------------------------");
            }

            echo "done";

        }catch (\Exception $e){
            Log::error($e->getMessage());
        }



    }
}
