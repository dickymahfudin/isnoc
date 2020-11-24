<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

class AjnSlaMultipleSheet implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */

    use Exportable;

    private $monthly, $site;

    public function __construct($datas, $site)
    {
        $this->monthly = $datas;
        $this->site = $site;
    }

    public function sheets(): array
    {
        $sheets = [];


        foreach ($this->monthly as $key => $value) {
            $title = $key;
            $sheets[] = new AjnSla($value, $title, $this->site);
        }

        return $sheets;
    }
}