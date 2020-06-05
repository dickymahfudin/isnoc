<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\models\ServiceCall;
use App\Models\ServiceCallDailys;
use Carbon\Carbon;

class ServiceCallsState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service:call';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push database, service calls open Daily';

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
        $datetime = new Carbon();
        $time_local = $datetime->format("Y-m-d");
        $datas = ServiceCall::where("status", "OPEN")->get();
        foreach ($datas as $data) {
            $newData = [
                "time_local" => $time_local,
                "nojs" => $data->nojs,
                "open_time" => $data->open_time,
                "error" => $data->error,
            ];
            ServiceCallDailys::create($newData);
        }
    }
}