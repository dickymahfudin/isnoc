<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use function GuzzleHttp\json_encode;

class PrtgController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getDataTotals(Request $request)
    {
        $id = $request->id;
        $sdate = $request->sdate;
        $edate = $request->edate;
        $username = $request->username;
        $password = $request->password;

        $data = $this->getPrtg([
            "id" => $id,
            "sdate" => $sdate,
            "edate" => $edate,
            "username" => $username,
            "password" => $password
        ]);
        return $data;
    }

    public static function getPrtg($datas)
    {
        $id = $datas["id"];
        $sdate = $datas["sdate"];
        $edate = $datas["edate"];
        $username = $datas["username"];
        $password = $datas["password"];

        $response = Http::withOptions([
            "headers" => [
                "Accept" => "application/xml",
                "Content-Type" => "application/xml"
            ],
            "verify" => false,
        ])->get("https://202.43.73.187/api/historicdata_totals.xml?id={$id}&sdate={$sdate}&edate={$edate}&username={$username}&password={$password}");

        $xml = $response->getBody()->getContents();
        $data = simplexml_load_string($xml);
        $name = trim($data->name);
        $parentdevicename = trim($data->parentdevicename);
        $uptimepercent = floatval($data->uptimepercent);
        $uptime = trim($data->uptime);
        $downtimepercent = floatval($data->downtimepercent);
        $downtime = trim($data->downtime);
        $average = trim($data->average);
        $error = trim($data->error);
        if ($error) {
            $send = [
                "name" => $name,
                "parentdevicename" => $parentdevicename,
                "uptimepercent" => $uptimepercent,
                "uptime" => $uptime,
                "downtimepercent" => $downtimepercent,
                "downtime" => $downtime,
                "average" => $average,
                "error" => $error,
            ];
        } else {
            $send = [
                "name" => $name,
                "parentdevicename" => $parentdevicename,
                "uptimepercent" => $uptimepercent,
                "uptime" => $uptime,
                "downtimepercent" => $downtimepercent,
                "downtime" => $downtime,
                "average" => $average
            ];
        }
        return response($send);
    }

    public function stateHistory(Request $request)
    {
        $id = $request->id;
        $sdate = $request->sdate;
        $edate = $request->edate;
        $username = $request->username;
        $password = $request->password;

        $parsing = explode('-', $sdate);
        $ndate = "{$parsing[0]}-{$parsing[1]}-{$parsing[2]} {$parsing[3]}:{$parsing[4]}";
        $newsdate = (new Carbon($ndate))->addMinutes(20)->format('Y-m-d-H-i');

        $responseState = Http::withOptions([
            'headers' => [
                'Accept' => 'application/xml',
                'Content-Type' => 'application/xml'
            ],
            'verify' => false,
        ])->get("https://202.43.73.187/api/historicdata.json?id={$id}&sdate={$sdate}&edate={$edate}&username={$username}&password={$password}&content=statehistory&datetime=0&columns=status,datetime");

        $responseLog  = Http::withOptions([
            'headers' => [
                'Accept' => 'application/xml',
                'Content-Type' => 'application/xml'
            ],
            'verify' => false,
        ])->get("https://202.43.73.187/api/historicdata.json?id={$id}&sdate={$sdate}&edate={$newsdate}&username={$username}&password={$password}&avg=0");

        return [
            'state' => $responseState->json(),
            'log' => $responseLog->json(),
        ];
    }
}