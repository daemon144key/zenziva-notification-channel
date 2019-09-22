<?php

namespace TuxDaemon\ZenzivaNotificationChannel\Commands;

use Illuminate\Console\Command;

class ZenzivaSendCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zenziva:send {phonenumber} {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS Request to Zenziva SMS Gateway';

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

            $phoneNumber = $this->argument('phonenumber');
            $message = $this->argument('message');

            $nbProcessed = $client->send($phoneNumber, $message);
            $this->info('[Zenziva] Total SMS sent : ' . $nbProcessed);
        } catch (\Exception $e) {
            $this->info('[Zenziva] Failed to send SMS!');
        }
    }
}
