<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\SlaPrtgController;
use App\Exports\SlaMonthlyMultipleSheet;
use App\Mail\SlaPrtgMonthlyMail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;

class EmailSlaMonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Sla Prtg Monthly and send email';

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
        Storage::delete('sla.xls');
        $sla = SlaPrtgController::monthly();
        $data = $sla->getOriginalContent();
        // dd($data);
        // dd($data["sla_prtg"]);
        Excel::store(new SlaMonthlyMultipleSheet($data), 'sla.xls');

        $to = ['pungki@sundaya.com', 'malik@sundaya.com'];
        $cc = ['maurice.adema@sundaya.com', 'desy@sundaya.com', 'tri@sundaya.com', 'dicky@sundaya.com', 'verena@sundaya.com'];

        Mail::to($to)
            ->cc($cc)
            ->send(new SlaPrtgMonthlyMail($data));
    }
}