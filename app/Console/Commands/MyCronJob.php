<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MyCronJob extends Command
{
    protected $signature = 'my:cronjob';
    protected $description = 'Description of my cron job';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        DB::table('cron_check')->insert([
            'check' => 'test ' . date("Y-m-d H:i:s")
        ]);
    
        // Output success message
        $this->info('Cron job executed successfully at ' . now());
    }
}
