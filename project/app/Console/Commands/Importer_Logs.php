<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Importer_Log;

class Importer_Logs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importer:show_logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows Logs from Importer';

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
        foreach (importer_log::all() as $importer_log) {
            echo $importer_log;
        }
    }
}
