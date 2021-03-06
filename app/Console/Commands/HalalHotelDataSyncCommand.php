<?php

namespace App\Console\Commands;

use App\Http\Controllers\ScriptController;
use Illuminate\Console\Command;

class HalalHotelDataSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'halal:hotel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync halal hotels data sync from csv';

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
     */
    public function handle()
    {
        return (new ScriptController())->halalHotels();
    }
}
