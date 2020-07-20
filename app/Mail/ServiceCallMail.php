<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\Api\ServiceCallsDailyController;

class ServiceCallMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = ServiceCallsDailyController::weekly();
        return $this->subject('Service Calls Weekly')->view('emails.emailWeekly', [
            "data" => $data["sum"]
        ]);

        // return $this->view('layouts.emailWeekly', [
        //     "data" => $data["sum"]
        // ])->attachFromStorage('/.gitignore');
    }
}