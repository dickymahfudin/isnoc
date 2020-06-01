<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NojsUser;
use App\Http\Controllers\Api\PrtgController;
use Carbon\Carbon;
use App\Models\SlaPrtg;

class SlaPrtgDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sla:prtg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get and Push data Sla Prtg Daily';

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
        $datas = NojsUser::all();

        $datetime = new Carbon();
        $datetime = ($datetime->subDays(1));
        $time_local = $datetime->format("Y-m-d");
        $sdate = $datetime->format("Y-m-d-00-00");
        $edate = $datetime->format("Y-m-d-23-59");
        $username = "Power APT";
        $password = "APT12345";

        foreach ($datas as $data) {
            $id = $data->id_lvdvsat;
            $new_data = [
                "id" => $id,
                "sdate" => $sdate,
                "edate" => $edate,
                "username" => $username,
                "password" => $password
            ];
            $temp = PrtgController::getPrtg($new_data);
            $sla = $temp->original;
            $push = [
                "time_local" => $time_local,
                "nojs" => $data->nojs,
                "site" => $data->site,
                "lvd1_vsat" => $sla["uptimepercent"]
            ];
            SlaPrtg::create($push);
        }
    }
}