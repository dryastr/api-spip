<?php

namespace App\Console\Commands\Mail;

use App\Mail\Notification;
use Illuminate\Console\Command;

class Send extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
            \Mail::to('syaiful.amir69@gmail.com')->send(new Notification('Testing Subject', 'testing message'));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
