<?php
namespace App\Exports;

use App\Models\EmployeeLeave;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class EmployeeLeavesExport implements FromCollection, WithHeadings, WithEvents, WithCustomStartCell
{
    protected $employee;
    protected $leaves;

    public function __construct($employee)
    {
        $this->employee = $employee;
        $this->leaves = EmployeeLeave::where('employee_id', $employee->id)
            ->with('leaveType')
            ->get();
    }

    public function collection()
    {
        return $this->leaves->map(function($leave, $index) {
            return [
                $index + 1,
                $leave->leaveType->name ?? '-',
                $leave->start_date->format('d.m.Y'),
                $leave->end_date->format('d.m.Y'),
                $leave->start_date->diffInDays($leave->end_date) + 1,
                $leave->reason ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No', 'Description', 'Start Date', 'End Date', 'Duration', 'Remark'
        ];
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Merge cells for title (Employee Name)
                $sheet->mergeCells('A1:F1');
                $sheet->setCellValue('A1', 'ဝန်ထမ်းအမည် - ' . $this->employee->name);
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Merge cells for leave type header
                $sheet->mergeCells('A2:F2');
                $sheet->setCellValue('A2', 'ပျက်ကွက်မှတ်တမ်း');
                $sheet->getStyle('A2')->getFont()->setBold(true);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Make headings bold
                $sheet->getStyle('A3:F3')->getFont()->setBold(true);

                // Set column widths
                foreach(range('A','F') as $col){
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            }
        ];
    }
}