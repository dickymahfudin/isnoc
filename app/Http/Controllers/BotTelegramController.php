<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NojsLogger;
use Carbon\Carbon;
use App\Models\NojsUser;
use Illuminate\Support\Facades\Http;

class BotTelegramController extends Controller
{
    public static function send()
    {
        $token = env('TOKEN_TELEGRAM');
        $id = env('ID_CHAT');
        $end = Carbon::now()->format('Y-m-d H:i:s');
        $start = (new Carbon($end))->subMinute(7)->format('Y-m-d H:i:s');
        $logger =  NojsLogger::whereBetween('time_local', [$start, $end])
            ->orderBy('time_local', 'desc')
            ->get();
        $temp = [];

        foreach ($logger as $value) {
            if ($value["batt_volt1"] <= 5000) {
                $nojs = NojsUser::find($value['nojs']);
                $volt =  $value["batt_volt1"] === null ? 0 : $value["batt_volt1"] / 100;
                array_push($temp, "{$nojs['site']} -> {$volt}v%0A");
            }
        }
        $text = count($temp) > 0 ? implode('', $temp) : "Aman Boy";
        Http::withOptions([
            "headers" => [
                "Accept" => "application/json",
                "Content-Type" => "application/json"
            ],
            "verify" => false,
        ])->get("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$id}&text={$text}");
    }
}