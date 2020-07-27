<?php

namespace App\Imports;

use App\Models\AjnLogger;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOption\None;

class AjnImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    use Importable;

    protected $name, $time;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function timeFormat(string $dateTime)
    {
        return "$dateTime[0]" . "$dateTime[1]" . "$dateTime[2]" . "$dateTime[3]-$dateTime[4]" . "$dateTime[5]-$dateTime[6]" . "$dateTime[7] $dateTime[9]" . "$dateTime[10]:$dateTime[11]" . "$dateTime[12]:$dateTime[13]" . "$dateTime[14]";
    }

    public function model(array $row)
    {
        try {
            $error = null;
            $site = (explode("_", $this->name)[0]);
            if ($site === "saritani") {
                return new AjnLogger([
                    'time_local' => $this->timeFormat($row[0]),
                    'site' => $site,
                    'pv_curr1' => ($row[2] !== 'error') ? intval($row[2]) : $error,
                    'batt_volt1' => ($row[1] !== 'error') ? intval($row[1]) : $error,
                ]);
            } else {
                return new AjnLogger([
                    'time_local' => $this->timeFormat($row[0]),
                    'site' => $site,
                    'load1' => ($row[1] !== 'error') ? intval($row[1]) : $error,
                    'load2' => ($row[2] !== 'error') ? intval($row[2]) : $error,
                    'edl1' => ($row[3] !== 'error') ?  intval(round($row[3]) / 10000000) : $error,
                    'edl2' => ($row[4] !== 'error') ?  intval(round($row[4]) / 10000000) : $error,
                    'edl3' => ($row[5] !== 'error') ?  intval(round($row[5]) / 10000000) : $error,
                    'pv_volt1' => ($row[6] !== 'error') ? intval($row[6]) : $error,
                    'pv_curr1' => ($row[7] !== 'error') ? intval($row[7]) : $error,
                    'batt_volt1' => ($row[8] !== 'error') ? intval($row[8]) : $error,
                    'pv_volt2' => ($row[9] !== 'error') ? intval($row[9]) : $error,
                    'pv_curr2' => ($row[10] !== 'error') ? intval($row[10]) : $error,
                    'batt_volt2' => ($row[11] !== 'error') ? intval($row[11]) : $error,
                ]);
            }
        } catch (\Throwable $th) {
            return (" $this->name has already been taken.");
        }
    }
}