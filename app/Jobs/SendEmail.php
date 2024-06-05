<?php

namespace App\Jobs;

use App\Mail\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
// use Mail;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $attachment;

    /**
     * Create a new job instance.
     *
     * @param array $attachment
     */
    public function __construct(array $attachment)
    {
        $this->attachment = $attachment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Adjust the content and recipient as needed
        $content = 'Attachment uploaded successfully.';

        Mail::to('darayastore01@gmail.com')->send(new Notification('Attachment Uploaded', $content));
    }
}
