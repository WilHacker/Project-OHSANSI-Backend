<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PublicacionExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(private Collection $datos) {}

    public function collection(): Collection
    {
        return $this->datos->map(fn ($row) => [
            $row['nombre_completo'],
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
            'Área',
            'Nivel',
            'Lugar',
            'Medalla',
        ];
    }

    public function title(): string
    {
        return 'Publicación Oficial';
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
            'A' => 35, 'B' => 22,
            'C' => 15, 'D' => 10, 'E' => 15,
        ];
    }
}
