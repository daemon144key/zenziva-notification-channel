<?php

namespace TuxDaemon\ZenzivaNotificationChannel\Commands;

use Illuminate\Console\Command;

class ZenzivaCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zenziva:checkbalance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check SMS Credit left';

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
        try {
            $client = app('zenzivasms');
            $creditLeft = $client->checkBalance();
            $this->info('[Zenziva] Credit left : '.$creditLeft);
        } catch (\Exception $e) {
            $this->info('[Zenziva] Failed to get credit info!');
        }
    }
}
