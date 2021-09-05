<?php

namespace App\Console\Commands;

use App\Downloader;
use App\Events\AuctionUserWasOutbidded;
use App\Services\SymbolPriceService;
use Illuminate\Console\Command;

class TestEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:testEvent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old data';


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
        event(new AuctionUserWasOutbidded());
    }
}
