<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

        $response = Http::withOptions([
            'headers' => [
                'Accept' => 'application/xml',
                'Content-Type' => 'application/xml'
            ],
            'verify' => false,
        ])->get("https://202.43.73.187/api/historicdata_totals.xml?id={$id}&sdate={$sdate}&edate={$edate}&username={$username}&password={$password}");

        $xml = $response->getBody()->getContents();
        // $data = new \SimpleXMLElement($xml);
        return $xml;
    }

    public function stateHistory(Request $request)
    {
        $id = $request->id;
        $sdate = $request->sdate;
        $edate = $request->edate;
        $username = $request->username;
        $password = $request->password;

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
        ])->get("https://202.43.73.187/api/historicdata.json?id={$id}&sdate={$sdate}&edate={$edate}&username={$username}&password={$password}&avg=0");

        return [
            'state' => $responseState->json(),
            'log' => $responseLog->json()
        ];
    }
}