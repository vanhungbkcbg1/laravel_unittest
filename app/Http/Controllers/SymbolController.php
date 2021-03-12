<?php

namespace App\Http\Controllers;

use App\Common\CSVReader;
use App\Models\NewSymbol;
use App\Models\Symbol;
use App\Models\SymbolPrice;
use App\Repositories\INewSymbolRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class SymbolController extends BaseController
{
    public function index()
    {
        $token = "0bf19074694728dec585d8bff5361593";

        $queryString = http_build_query([
            'access_key' => $token,
            "limit" => 400
        ]);

        $ch = curl_init(sprintf('%s?%s', 'http://api.marketstack.com/v1/exchanges/XSTC/tickers', $queryString));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $json = curl_exec($ch);
        curl_close($ch);

        $apiResult = json_decode($json, true);
//        print_r($apiResult);

        foreach ($apiResult["data"]["tickers"] as $ticker) {
            $symbol = new Symbol();
            $symbol->name = $ticker["symbol"];
            $symbol->company = $ticker["name"];
            $symbol->save();
        }
        die();
    }

    public function newSymbol(INewSymbolRepository $repository){
        $handler= fopen("test.csv",'r');

        $data =CSVReader::readFileV2($handler);
        foreach ($data as $name=>$item){

            $repository->create([
                "name"=>$name
            ]);
        }

    }

    public function stockPrice()
    {
        $token = "5fa56a56a0ffa6f258bc0d959c878d8a";

        $queryString = http_build_query([
            'access_key' => $token,
            "limit" => 50
        ]);

        $symbols = Symbol::all();
        foreach ($symbols as $symbol) {

            $url = sprintf("http://api.marketstack.com/v1/tickers/%s/eod", $symbol->name);
            $url = sprintf('%s?%s', $url, $queryString);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $json = curl_exec($ch);
            curl_close($ch);

            $apiResult = json_decode($json, true);

            $data = [];

            foreach ($apiResult['data']['eod'] as $item) {
                $date = new \DateTime($item['date']);

                $data[] = [
                    "date" => $date,
                    "symbol" => $item['symbol'],
                    "price" => $item['close'],
                    "volume" => $item['volume'],
                ];

            }

            SymbolPrice::insert($data);

            usleep(1000);
        }

        echo "done";


    }
}
