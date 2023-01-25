<?php

namespace App\Console\Commands;

use App\Message;
use Illuminate\Console\Command;

class ScheduleMessagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change Message Status according to its date of publication';

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
        $messages = Message::whereNotNull('publish_datetime')
            ->where('publish_datetime', '<', new \DateTime())
            ->where('status_id', 2)
            ->get();

        foreach ($messages as $key => $message) {
            $message->status_id = 3;
            $message->save();
        }
    }
}
