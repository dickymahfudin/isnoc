<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ReportSla implements FromArray, ShouldAutoSize, WithHeadings, WithTitle, WithEvents, WithStrictNullComparison
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $data, $title, $site, $sumDuration, $sumBattVolt;

    public function __construct($data)
    {
        $this->data = $data['data'];
        $this->title = (new Carbon($data['date']))->format('F Y');
        $this->site = $data['site'];
        $this->sumDuration = $data['sum_duration'];
        $this->sumBattVolt = $data['sum_batt_volt'];
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
                "Duration", $this->sumDuration
            ], ["Batt Volt", $this->sumBattVolt],
            [
                "Date Time",
                "Eh1",
                "Eh2",
                "Vsat Curr",
                "Bts Curr",
                "Batt Volt",
                "Edl1",
                "Edl2",
                "LVD1",
                "LVD2",
                "Duration",
                "Real",
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

                $event->sheet->getDelegate()->mergeCells('A1:L1');
                $event->sheet->getDelegate()->mergeCells('A2:L2');
                $event->sheet->getDelegate()->mergeCells('A3:L3');
                $event->sheet->getDelegate()->getStyle('A1:L1')->applyFromArray($styleDescription);
                $event->sheet->getDelegate()->getStyle('A2:L2')->applyFromArray($styleDescription);
                $event->sheet->getDelegate()->getStyle('A3:L3')->applyFromArray($styleDescription);
                $event->sheet->getDelegate()->getStyle('A4:C4')->applyFromArray($styleDescription);
                $event->sheet->getDelegate()->getStyle('A5:C5')->applyFromArray($styleDescription);
                $event->sheet->getDelegate()->getStyle('A6:L6')->applyFromArray($styleHeader);

                $event->sheet->getDelegate()->getStyle('A7:L' . $dataCount)->applyFromArray($styleMain);
            },
        ];
    }
}
