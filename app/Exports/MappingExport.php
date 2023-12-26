<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class MappingExport implements WithHeadings, ShouldAutoSize, WithEvents
{
    protected $courses;
    protected $maxClosLengths;

    public function __construct($courses, $maxClosLengths)
    {
        $this->courses = $courses;
        $this->maxClosLengths = $maxClosLengths;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->mergeCells('A1:S1');
                $event->sheet->setCellValue('A1', 'Master Mapping of CLO with PLO/SO and Learning Domains for Construction Management Program');
                $event->sheet->getDelegate()->getStyle('C1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('C1')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(14);
                $ploSoValues = ['PLO/SO', 'PLO1/SO1', 'PLO2/SO2', 'PLO3/SO3', 'PLO4/SO4', 'PLO5/SO5', 'PLO6/SO6', 'PLO7/SO7'];
                $row = 3;
                foreach ($ploSoValues as $index => $value) {
                    // Get the number of rows for merging
                    $mergeRows = 1;
                    if (!($index == 0)) {

                        $mergeRows = $this->maxClosLengths[$index];

                        // Merge and center cells
                        $event->sheet->mergeCells("A{$row}:A" . ($row + $mergeRows - 1));
                    }
                    $event->sheet->setCellValue("A{$row}", $value);
                    $event->sheet->getStyle("A{$row}:A" . ($row + $mergeRows - 1))
                        ->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $row += $mergeRows;
                }
                $courseColumn = 'B';
                foreach ($this->courses as $index => $course) {
                    $courseId = $course['id'];
                    $levelInitial = strtoupper(substr($course['level'], 0, 1));
                    
                    $event->sheet->setCellValue($courseColumn . 3, $courseId."-".$levelInitial);
                    $color = $this->getColorForCourseId($courseId);
                    $event->sheet->getStyle($courseColumn . 3)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB($color);
                    $event->sheet->getStyle($courseColumn . 3)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $event->sheet->getStyle($courseColumn . 3)
                        ->getFont()
                        ->setBold(true)
                        ->setSize(14);
                    $row = 4;
                    foreach ($course['plos'] as $plo) {

                        for ($i = 0; $i < $this->maxClosLengths[$plo['id']]; $i++) {
                            if (isset($plo['clos'][$i])) {
                                $event->sheet->setCellValue($courseColumn . $row, $this->getCLOCellVlaue($plo['clos'][$i]));
                            }
                            $row++;
                        }
                    }
                    $courseColumn++;
                }
                $columns = range('A', ++$courseColumn);
                foreach ($columns as $column) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }

                for ($row = 1; $row <= $event->sheet->getHighestRow(); $row++) {
                    $event->sheet->getRowDimension($row)->setRowHeight(-1);
                }
            },
        ];
    }
    private function getCLOCellVlaue($clo)
    {
        $clotype = $clo['type'];
        $cloDescription = $clo['description'];
        return strtoupper($clotype[0]) . "-{$cloDescription}";
    }
    private function getColorForCourseId($courseId)
    {
        preg_match('/\d/', $courseId, $matches);
    
        // Use the null coalescing operator to set a default value if $matches is empty
        $firstDigit = $matches ? $matches[0] : '1';
    
        switch ($firstDigit) {
            case '2':
                return '8EA9DB';
            case '3':
                return 'FFD966';
            case '4':
                return 'C9C9C9';
            default:
                return 'FFFFFFFF';
        }
    }
    public function headings(): array
    {
        return [];
    }
}
