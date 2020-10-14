<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class SlaPrtgMonthlyMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $data, $kpi, $detail, $start, $end;

    public function __construct($datas)
    {
        $this->data = $datas["sla_2"];
        $this->kpi = $datas["kpi"];
        $this->detail = $datas["detail"];
        $this->start = (new Carbon($this->detail["start"]))->format('d F Y');
        $this->end = (new Carbon($this->detail["end"]))->format('d F Y');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Sla Prtg Monthly")->view('emails.emailSlaMonthly', [
            "data" => $this->data,
            "kpi" => $this->kpi,
            "start" => $this->start,
            "end" => $this->end
        ])->attachFromStorage('/sla.xls');
    }
}