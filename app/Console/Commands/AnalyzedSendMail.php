<?php

namespace App\Console\Commands;

use App\Downloader;
use App\Mail\AnalyzedMail;
use App\Services\AnalyzedService;
use App\Services\SymbolPriceService;
use App\Services\TextFileWriterService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AnalyzedSendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:analyzed-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mail after analyzed';

    private $service;


    /**
     * AnalyzedSendMail constructor.
     * @param AnalyzedService $service
     */
    public function __construct(AnalyzedService $service)
    {
        parent::__construct();
        $this->service =$service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data =$this->service->getAllData();
        $fileName =public_path('process_data.txt');
        TextFileWriterService::write($fileName,$data);
        Mail::send(new AnalyzedMail());
    }
}
