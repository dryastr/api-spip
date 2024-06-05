<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $namaPenerima;
    public $namaPemdaOpd;
    public $linkEvaluasi;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($namaPenerima, $namaPemdaOpd, $linkEvaluasi)
    {
        $this->namaPenerima = $namaPenerima;
        $this->namaPemdaOpd = $namaPemdaOpd;
        $this->linkEvaluasi = $linkEvaluasi;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this->markdown('emails.invitation')
        ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
        ->subject('[' . env('MAIL_FROM_NAME') . '][' . date('d M Y') . '] ' . $this->namaPemdaOpd)
        ->with([
            'message' => $this->namaPenerima,
            'linkevaluasi' => $this->linkEvaluasi,
        ]);                  

     }   
}