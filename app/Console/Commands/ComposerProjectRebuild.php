<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;


class ComposerProjectRebuild extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'composer:project-rebuild';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '專案重置';

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
        // 生產環境不允許操作 composer demo
        if (strpos(env('APP_ENV'), 'prod') !== false) {
            $this->error('專案環境不允許執行此操作，當前環境為：' . env('APP_ENV'));
        } else {
            // @php artisan config:clear
            Artisan::call('optimize:clear');
            $this->line('php artisan optimize clear... success');

            // @php artisan migrate:fresh
            Artisan::call('migrate:fresh');
            $this->line('php artisan migrate:fresh... success');

            // @php artisan db:seed --class=DatabaseSeeder
            Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
            $this->line('php artisan db:seed --class=DatabaseSeeder... success');

            // @php artisan jwt:secret
            Artisan::call('jwt:secret', ['-f' => true]);
            $this->line('php artisan jwt:secret -f ... success');

            // remove public temp file
            $file = new Filesystem;
            $file->cleanDirectory(config('filesystems.disks.storage-public.root'));
            $this->line('clear all public files ... success');

            $this->info('project-rebuild complete.');
        }
    }

}
