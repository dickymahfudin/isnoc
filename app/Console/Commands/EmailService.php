<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ServiceCallMail;

class EmailService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Service Call Weekly to Pak Maurice';

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
        $to = 'maurice.adema@sundaya.com';
        $cc = ['malik@sundaya.com', 'pungki@sundaya.com', 'dicky@sundaya.com'];
        Mail::to($to)
            ->cc($cc)
            ->send(new ServiceCallMail());
    }
}