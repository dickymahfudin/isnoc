<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use App\Http\Controllers\Api\NojsLoggersController;

class AjnSla implements FromArray, ShouldAutoSize, WithHeadings, WithTitle, WithEvents, WithStrictNullComparison
{
    /**
     * @return \Illuminate\Support\Collection
     */

    private $data, $title, $site, $sum, $sla;

    public function __construct($data,  String $title, String $site)
    {
        $this->data = $data;
        $this->title = (new Carbon($title))->format('F Y');
        $this->site = $site;
        $collection = collect($data);
        $this->sum = $collection->sum('time');
        $endDate = (new Carbon($this->title))->addMonth(1)->format('F Y');
        $timeSec = Carbon::parse($this->title)->diffInSeconds(Carbon::parse($endDate));
        $this->sla = round((($this->sum / $timeSec) * 100), 2);
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            [
                "SLA Site $this->site"
            ], ["LOG POWER JOULE STORE PRO"],
            [
                "$this->title"
            ], [
                "Up Time Total: " . NojsLoggersController::secToTime($this->sum)
            ], ["SLA : $this->sla%"],
            [
                "Date",
                "time",
                "Up Time",
                "Load1",
                "Load2",
                "edl1",
                "edl2",
                "edl3",
                "pv_volt1",
                "pv_curr1",
                "batt_volt1",
                "pv_volt2",
                "pv_curr2",
                "batt_volt2",
            ]
        ];
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

        $styleSlaRed = [
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

        $styleSlaYellow = [
            'font' => [
                'color' => [
                    'argb' => 'FF000000',
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => [
                    'argb' => 'FFF9F44D',
                ]
            ],
        ];

        $styleSlaGreen = [
            'font' => [
                'color' => [
                    'argb' => 'FFFFFFFF',
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => [
                    'argb' => 'FF2CAA54',
                ]
            ],
        ];
        return [
            AfterSheet::class => function (AfterSheet $event) use ($styleHeader, $styleDescription, $styleMain, $styleSlaRed, $styleSlaYellow, $styleSlaGreen) {
                $dataCount = count($this->data) + 6;

                $event->sheet->getDelegate()->mergeCells('A1:N1');
                $event->sheet->getDelegate()->mergeCells('A2:N2');
                $event->sheet->getDelegate()->mergeCells('A3:N3');
                $event->sheet->getDelegate()->mergeCells('A4:C4');
                $event->sheet->getDelegate()->getStyle('A1:N1')->applyFromArray($styleDescription);
                $event->sheet->getDelegate()->getStyle('A2:N2')->applyFromArray($styleDescription);
                $event->sheet->getDelegate()->getStyle('A3:N3')->applyFromArray($styleDescription);
                $event->sheet->getDelegate()->getStyle('A6:N6')->applyFromArray($styleHeader);
                $event->sheet->getDelegate()->getStyle('A7:N' . $dataCount)->applyFromArray($styleMain);

                // foreach ($this->data as $key => $value) {
                //     $temp = $key + 6;
                //     $row = "A$temp:G$temp";
                //     if ($value["lvd1_vsat"] < 91) {
                //         $event->sheet->getDelegate()->getStyle($row)->applyFromArray($styleSlaRed);
                //     } else if ($value["lvd1_vsat"] <= 95 && $value["lvd1_vsat"] >= 91) {
                //         $event->sheet->getDelegate()->getStyle($row)->applyFromArray($styleSlaYellow);
                //     } else {
                //         $event->sheet->getDelegate()->getStyle($row)->applyFromArray($styleSlaGreen);
                //     }
                // }
            },
        ];
    }
}