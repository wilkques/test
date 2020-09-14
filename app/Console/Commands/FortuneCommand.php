<?php

namespace App\Console\Commands;

use App\Service\Crawler\Fortune\CrawlerService;
use Illuminate\Console\Command;

class FortuneCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:fortune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '抓取運勢資料';

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
     * @return int
     */
    public function handle()
    {
        (new CrawlerService)->exec();
    }
}
