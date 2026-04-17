<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClasificadosExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(private Collection $datos) {}

    public function collection(): Collection
    {
        return $this->datos->map(fn ($row) => [
            $row['nombre_completo'],
            $row['institucion'],
            $row['departamento'],
            $row['area'],
            $row['nivel'],
            $row['nota'],
            $row['resultado'],
            $row['observacion'],
        ]);
    }

    public function headings(): array
    {
        return [
            'Nombre Completo',
            'Institución',
            'Departamento',
            'Área',
            'Nivel',
            'Nota',
            'Resultado',
            'Observación',
        ];
    }

    public function title(): string
    {
        return 'Clasificación';
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
            'A' => 30, 'B' => 28, 'C' => 18,
            'D' => 18, 'E' => 15, 'F' => 8,
            'G' => 18, 'H' => 35,
        ];
    }
}
