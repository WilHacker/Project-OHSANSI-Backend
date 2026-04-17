<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CeremoniaExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(private Collection $datos) {}

    public function collection(): Collection
    {
        return $this->datos->map(fn ($row) => [
            $row['nombre_completo'],
            $row['unidad_educativa'],
            $row['area'],
            $row['nivel'],
            $row['posicion'],
            $row['medalla'],
        ]);
    }

    public function headings(): array
    {
        return [
            'Nombre Completo',
            'Unidad Educativa',
            'Área',
            'Nivel',
            'Posición',
            'Medalla',
        ];
    }

    public function title(): string
    {
        return 'Ceremonia de Premiación';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    /** @return array<string, int|float> */
    public function columnWidths(): array
    {
        return [
            'A' => 30, 'B' => 30,
            'C' => 20, 'D' => 15, 'E' => 10, 'F' => 15,
        ];
    }
}
