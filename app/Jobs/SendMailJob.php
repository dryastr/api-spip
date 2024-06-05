<?php

namespace App\Jobs;

use App\Mail\InvitationEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $namaPenerima;
    public $namaPemdaOpd;
    public $linkEvaluasi;
    public $emailPenerima;

    /**
     * Create a new job instance.
     *
     * @param string $namaPenerima
     * @param string $namaPemdaOpd
     * @param string $linkEvaluasi
     */
    public function __construct($emailPenerima,$namaPenerima, $namaPemdaOpd, $linkEvaluasi)
    {
        $this->emailPenerima = $emailPenerima;
        $this->namaPenerima = $namaPenerima;
        $this->namaPemdaOpd = $namaPemdaOpd;
        $this->linkEvaluasi = $linkEvaluasi;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Kirim email InvitationEmail
        Mail::to($this->emailPenerima)
            ->send(new InvitationEmail($this->namaPenerima, $this->namaPemdaOpd, $this->linkEvaluasi));
    }
}
