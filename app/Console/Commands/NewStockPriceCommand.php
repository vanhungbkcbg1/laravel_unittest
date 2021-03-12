<?php

namespace App\Console\Commands;

use App\Common\CSVReader;
use App\Downloader;
use App\Models\NewSymbol;
use App\Models\Symbol;
use App\Models\SymbolPrice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;

class NewStockPriceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:new-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get 50 day Stock data from cophieu 68';

    protected $reader;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CSVReader $reader)
    {
        parent::__construct();
        $this->reader = $reader;

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



            $days = [
                '11-03-2021',
//                '10-03-2021',
//                '09-03-2021',
//                '08-03-2021',
//                '05-03-2021',
//                '04-03-2021',
//                '03-03-2021',
//                '02-03-2021',
//                '01-03-2021',
//                '26-02-2021',
//                '25-02-2021',
//                '24-02-2021',
//                '23-02-2021',
//                '22-02-2021',
//                '19-02-2021',
//                '18-02-2021',
//                '17-02-2021',
//                '09-02-2021',
//                '08-02-2021',
//                '05-02-2021',
//                '04-02-2021',
//                '03-02-2021',
//                '02-02-2021',
//                '01-02-2021',
//                '29-01-2021',
//                '28-01-2021',
//                '27-01-2021',
//                '26-01-2021',
//                '25-01-2021',
//                '22-01-2021',
//                '21-01-2021',
//                '20-01-2021',
//                '19-01-2021',
//                '18-01-2021',
//                '15-01-2021',
//                '14-01-2021',
//                '13-01-2021',
//                '12-01-2021',
//                '11-01-2021',
//                '08-01-2021',
//                '07-01-2021',
//                '06-01-2021',
//                '05-01-2021',
//                '04-01-2021',
//                '31-12-2020',
//                '30-12-2020',
//                '29-12-2020',
//                '28-12-2020',
//                '25-12-2020',
//                '24-12-2020',
//                '23-12-2020'
            ];
            Log::info("-------------------------Begin-------------------------------");

            $symbols = NewSymbol::all();

            foreach ($days as $day) {
                //download file
                Downloader::download($day);

                // read file just download
                $stockOfDay = $this->reader->readFile(fopen(public_path("file.csv"), 'r'));
                //process and insert to stock price table
                $data = [];
                foreach ($symbols as $symbol) {

                    if (isset($stockOfDay[$symbol->name])) {
                        $stockDataOfSymbol = $stockOfDay[$symbol->name];
                        $data[] = [
                            "date" => $stockDataOfSymbol['date'],
                            "symbol" => $symbol->name,
                            "price" => $stockDataOfSymbol['close'],
                            "volume" => $stockDataOfSymbol['volume'],
                        ];

                        Log::error("Done " . $symbol->name);
                    } else {
                        Log::error("StockError " . $symbol->name);
                    }
                }

                if ($data) {
                    SymbolPrice::insert($data);
                }

            }

            echo "done";

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
