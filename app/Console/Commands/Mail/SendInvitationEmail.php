<?php

namespace App\Console\Commands\Mail;

use App\Mail\InvitationEmail;
use App\Mail\Notification;
use Illuminate\Console\Command;

class SendInvitationEmail extends Command
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
            \Mail::to('akhmadsukron43@gmail.com')->send(new InvitationEmail('kronz','jabar','href//poke'));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
