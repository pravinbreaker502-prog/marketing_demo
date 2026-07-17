<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestScheduler extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:test-scheduler';

    /**
     * The console command description.
     */
    protected $description = 'Test Laravel Scheduler';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        file_put_contents(
            storage_path('logs/scheduler-test.txt'),
            now() . " Scheduler executed\n",
            FILE_APPEND
        );

        $this->info('Scheduler Executed');

        return self::SUCCESS;
    }
}