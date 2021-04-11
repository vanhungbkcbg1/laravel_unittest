<?php

namespace App\Console\Commands;

use App\Downloader;
use App\Services\SymbolPriceService;
use Illuminate\Console\Command;

class DeleteOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:delete-old-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old data';

    private $service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SymbolPriceService $service)
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

        $this->service->deleteOldData();
    }
}
