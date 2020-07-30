<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Http\Controllers\Api\SlaPrtgController;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;

use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class SlaMonthly implements FromArray, ShouldAutoSize, WithHeadings, WithTitle, WithEvents, WithStrictNullComparison
{
    /**
     * @return \Illuminate\Support\Collection
     */

    private $data, $title, $detail, $start, $end;

    public function __construct($data,  String $title)
    {
        $this->data = $data["data"];
        $this->detail = $data["detail"];
        $this->start = $this->detail["start"];
        $this->end = $this->detail["end"];

        $this->title = $title;
    }

    public function array(): array
    {
        if ($this->title === "SLA") {
            return $this->data;
        } else {
            return array_reverse($this->data["data"]);
        }
    }

    public function headings(): array
    {
        $sdate = (new Carbon($this->start))->format('d M Y');
        $edate = (new Carbon($this->end))->format('d M Y');

        if ($this->title === "SLA") {
            return [
                [
                    'SLA PRTG'
                ], [
                    '100 Site (Sundaya)'
                ], [
                    "$sdate - $edate"
                ], [''],
                [
                    'Nojs',
                    'Site',
                    'LC',
                    'Sla lvd1 Vsat',
                    'Up time',
                    'Down time',
                ]
            ];
        } else {
            $detail = $this->data["detail"];
            $site = $detail["site"];
            $lc = $detail["lc"];
            return [
                [
                    "SLA 2 Site $site ($lc)"
                ], ["LOG POWER JOULE STORE PRO"],
                [
                    "$sdate - $edate"
                ], [''],
                [
                    "Date",
                    "Up Time",
                    "Nojs",
                    "Eh1",
                    "Eh2",
                    "Vsat Curr",
                    "Bts Curr",
                    "Load3",
                    "Batt Volt1",
                    "Batt Volt2",
                    "Edl1",
                    "Edl2",
                ]
            ];
        }
    }

    public function title(): string
    {
        return $this->title;
    }


    public function registerEvents(): array
    {
        $styleDescription = [
            'font' => [
                'bold' => true,
                'size' => 14
            ],

            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ];

        $styleHeader = [
            'font' => [
                'bold' => true,
                'size' => 12.5
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
            ],
        ];

        $styleMain = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
            ],
        ];

        $styleSla = [
            'font' => [
                'color' => [
                    'argb' => 'FFFFFFFF',
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => [
                    'argb' => 'FFFF0000',
                ]
            ],
        ];

        if ($this->title === "SLA") {
            return [
                AfterSheet::class => function (AfterSheet $event) use ($styleHeader, $styleDescription, $styleMain, $styleSla) {
                    $dataCount = count($this->data) + 5;
                    $slaMin = $this->detail["site_loop"];
                    $event->sheet->getDelegate()->mergeCells('A1:F1');
                    $event->sheet->getDelegate()->mergeCells('A2:F2');
                    $event->sheet->getDelegate()->mergeCells('A3:F3');
                    $event->sheet->getDelegate()->getStyle('A1:F1')->applyFromArray($styleDescription);
                    $event->sheet->getDelegate()->getStyle('A2:F2')->applyFromArray($styleDescription);
                    $event->sheet->getDelegate()->getStyle('A3:F3')->applyFromArray($styleDescription);
                    $event->sheet->getDelegate()->getStyle('A5:F5')->applyFromArray($styleHeader);
                    $event->sheet->getDelegate()->getStyle('A6:F' . $dataCount)->applyFromArray($styleMain);

                    foreach ($slaMin as $value) {
                        $temp = $value + 5;
                        $row = "A$temp:F$temp";
                        $event->sheet->getDelegate()->getStyle($row)->applyFromArray($styleSla);
                    }
                },
            ];
        } else {
            return [
                AfterSheet::class => function (AfterSheet $event) use ($styleHeader, $styleDescription, $styleMain) {
                    $dataCount = count($this->data["data"]) + 5;
                    $event->sheet->getDelegate()->mergeCells('A1:L1');
                    $event->sheet->getDelegate()->mergeCells('A2:L2');
                    $event->sheet->getDelegate()->mergeCells('A3:L3');
                    $event->sheet->getDelegate()->getStyle('A1:L1')->applyFromArray($styleDescription);
                    $event->sheet->getDelegate()->getStyle('A2:L2')->applyFromArray($styleDescription);
                    $event->sheet->getDelegate()->getStyle('A3:L3')->applyFromArray($styleDescription);
                    $event->sheet->getDelegate()->getStyle('A5:L5')->applyFromArray($styleHeader);
                    $event->sheet->getDelegate()->getStyle('A6:L' . $dataCount)->applyFromArray($styleMain);
                },
            ];
        }
    }
}