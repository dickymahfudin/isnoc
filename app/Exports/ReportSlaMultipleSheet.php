<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

class ReportSlaMultipleSheet implements WithMultipleSheets
{
    use Exportable;

    private $data;

    public function __construct($datas)
    {
        $this->data = $datas;
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->data as $value) {
            $sheets[] = new ReportSla($value);
        }

        return $sheets;
    }
}