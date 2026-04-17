<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CertificadosExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(private Collection $datos) {}

    public function collection(): Collection
    {
        return $this->datos->map(fn ($row) => [
            $row['nombre_completo'],
            $row['unidad_educativa'],
            $row['departamento'],
            $row['area'],
            $row['nivel'],
            $row['nota'],
            $row['posicion'],
            $row['medalla'],
            $row['tutor_academico'],
            $row['responsable_area'],
        ]);
    }

    public function headings(): array
    {
        return [
            'Nombre Completo',
            'Unidad Educativa',
            'Departamento',
            'Área',
            'Nivel',
            'Nota',
            'Posición',
            'Medalla',
            'Tutor Académico',
            'Responsable de Área',
        ];
    }

    public function title(): string
    {
        return 'Certificados';
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
            'A' => 30, 'B' => 30, 'C' => 20,
            'D' => 20, 'E' => 15, 'F' => 8,
            'G' => 10, 'H' => 15, 'I' => 30, 'J' => 30,
        ];
    }
}
