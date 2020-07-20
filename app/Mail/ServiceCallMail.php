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
        $time_local = [];
        $sum = [];
        foreach ($data["sum"] as $value) {
            array_push($time_local, $value["time_local"]);
            array_push($sum, $value["sum"] / 100);
        };

        $chartConfigArr = array(
            'type' => 'line',
            'data' => [
                'labels' => $time_local,
                'datasets' => [
                    [
                        'label' => "service",
                        "borderColor" => "#3e95cd",

                        'data' => $sum,
                        "fill" => false

                    ]
                ]
            ],
            'options' => [
                'scales' => [
                    'yAxes' => [
                        'ticks' => [
                            'beginAtzero' => true
                        ]
                    ]
                ],
                'legend' => [
                    'display' => false,
                    'labels' => [
                        'defaultFontSize' => 7
                    ]
                ]
            ]
        );

        $chartConfig = json_encode($chartConfigArr);
        $chartUrl = 'https://quickchart.io/chart?w=450&h=150&c=' . urlencode($chartConfig);

        return $this->subject('Service Calls Weekly')->view('emails.emailWeekly', [
            "data" => $data["sum"],
            "url" => $chartUrl
        ]);

        // return $this->view('layouts.emailWeekly', [
        //     "data" => $data["sum"]
        // ])->attachFromStorage('/.gitignore');
    }
}