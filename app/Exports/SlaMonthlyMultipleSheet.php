<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

class SlaMonthlyMultipleSheet implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */

    use Exportable;

    private $slaPrtg, $sla2, $detail;

    public function __construct($datas)
    {
        $this->detail = $datas["detail"];
        $this->slaPrtg = [
            'detail' => $this->detail,
            'data' => $datas["sla_prtg"]
        ];
        $this->sla2 = $datas["sla_2"];
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new SlaMonthly($this->slaPrtg,  "SLA");

        foreach ($this->sla2 as $value) {
            $title = $value['detail']['nojs'] . " " . $value['detail']['site'];
            $sheets[] = new SlaMonthly([
                "data" => $value,
                "detail" => $this->detail
            ], $title);
        }

        return $sheets;
    }
}