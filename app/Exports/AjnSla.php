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

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;

class AjnSla implements FromArray, ShouldAutoSize, WithHeadings, WithTitle, WithEvents, WithStrictNullComparison, WithColumnFormatting
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
        $this->sum = round($collection->sum('SLA') / count($collection), 1);
        // $endDate = (new Carbon($this->title))->addMonth(1)->format('F Y');
        // $timeSec = Carbon::parse($this->title)->diffInSeconds(Carbon::parse($endDate));
        // $this->sla = round((($this->sum / $timeSec) * 100), 2);
    }

    public function array(): array
    {
        return $this->data;
    }

    public function columnFormats(): array
    {
        return [
            'F' => '#,##0',
            'N' => '#,##0',
        ];
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
                "AVG SLA", $this->sum
            ], [],
            [
                "Date",
                "Up Time",
                "SLA (%)",
                "H1",
                "H2",
                "H",
                "V min",
                "V avg",
                "V max",
                "DV",
                "E1",
                "E2",
                "E3",
                "E",
                "Data",
                "Energy",
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
            'font' => [
                'bold' => false,
                'size' => 12
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
            ],
        ];

        $styleHeaderRed = [
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
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => [
                    'argb' => 'FFFF0000',
                ]
            ],
        ];

        $styleHeaderBlue = [
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
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => [
                    'argb' => 'FF02FCFF',
                ]
            ],
        ];

        $styleHeaderYellow = [
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
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => [
                    'argb' => 'FFF9F44D',
                ]
            ],
        ];

        $styleHeaderGreen = [
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
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => [
                    'argb' => 'FF2CAA54',
                ]
            ],
        ];
        return [
            AfterSheet::class => function (AfterSheet $event) use ($styleHeader, $styleDescription, $styleMain, $styleHeaderRed, $styleHeaderBlue, $styleHeaderYellow, $styleHeaderGreen) {
                $dataCount = count($this->data) + 6;

                $event->sheet->getDelegate()->mergeCells('A1:N1');
                $event->sheet->getDelegate()->mergeCells('A2:N2');
                $event->sheet->getDelegate()->mergeCells('A3:N3');
                $event->sheet->getDelegate()->mergeCells('D5:F5');
                $event->sheet->getDelegate()->mergeCells('G5:J5');
                $event->sheet->getDelegate()->mergeCells('K5:N5');
                $event->sheet->getDelegate()->mergeCells('O5:P5');
                $event->sheet->setCellValue('D5', 'Harvest (kWh/day)');
                $event->sheet->setCellValue('G5', 'Store Voltage (V)');
                $event->sheet->setCellValue('K5', 'Enjoy (kWh/day)');
                $event->sheet->setCellValue('O5', 'System Down Time (%)');
                $event->sheet->getDelegate()->getStyle('A1:N1')->applyFromArray($styleDescription);
                $event->sheet->getDelegate()->getStyle('A2:N2')->applyFromArray($styleDescription);
                $event->sheet->getDelegate()->getStyle('A3:N3')->applyFromArray($styleDescription);
                $event->sheet->getDelegate()->getStyle('A4:C4')->applyFromArray($styleDescription);
                $event->sheet->getDelegate()->getStyle('D5:F5')->applyFromArray($styleHeaderGreen);
                $event->sheet->getDelegate()->getStyle('G5:J5')->applyFromArray($styleHeaderBlue);
                $event->sheet->getDelegate()->getStyle('K5:N5')->applyFromArray($styleHeaderRed);
                $event->sheet->getDelegate()->getStyle('O5:P5')->applyFromArray($styleHeaderYellow);
                $event->sheet->getDelegate()->getStyle('A6:N6')->applyFromArray($styleHeader);

                $event->sheet->getDelegate()->getStyle('D6:F6')->applyFromArray($styleHeaderGreen);
                $event->sheet->getDelegate()->getStyle('G6:J6')->applyFromArray($styleHeaderBlue);
                $event->sheet->getDelegate()->getStyle('K6:N6')->applyFromArray($styleHeaderRed);
                $event->sheet->getDelegate()->getStyle('O6:P6')->applyFromArray($styleHeaderYellow);

                $event->sheet->getDelegate()->getStyle('A7:P' . $dataCount)->applyFromArray($styleMain);

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