<?php

namespace App\Exports;

use App\Models\GroupKategori;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StatistikSheetExport implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    protected GroupKategori $group;
    protected array $items; // [id => nama_item]

    public function __construct(GroupKategori $group)
    {
        $this->group = $group;
        $this->items = $group->groupKategoriItems()->pluck('nama_item', 'id')->toArray();
    }

    public function array(): array
    {
        $itemIds   = array_keys($this->items);
        $itemNames = array_values($this->items);

        // Row 1: item_id (tersembunyi) — diawali __META__ sebagai penanda
        $metaRow = ['__META__', ...$itemIds];

        // Row 2: header yang user lihat
        $headerRow = ['Tahun', ...$itemNames];

        // Row 3 dst: contoh baris kosong untuk 3 tahun terakhir
        $tahunSekarang = now()->year;
        $dataRows = array_map(
            fn($tahun) => array_fill(0, count($this->items) + 1, '') + [0 => $tahun],
            range($tahunSekarang, $tahunSekarang - 2)
        );

        return [$metaRow, $headerRow, ...$dataRows];
    }

    public function styles(Worksheet $sheet): array
    {
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
            count($this->items) + 1
        );

        // Sembunyikan row 1 (meta row)
        $sheet->getRowDimension(1)->setVisible(false);

        // Style header (row 2) → hijau
        $sheet->getStyle("A2:{$lastCol}2")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '166534']],
        ]);

        // Style baris data
        $totalRows = count($this->array());
        $sheet->getStyle("A3:{$lastCol}{$totalRows}")->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F9FAFB']],
        ]);

        // Kolom tahun → bold
        $sheet->getStyle("A3:A{$totalRows}")->getFont()->setBold(true);

        return [];
    }

    public function columnWidths(): array
    {
        $widths = ['A' => 10];
        foreach (range(2, count($this->items) + 1) as $i) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            $widths[$col] = 16;
        }
        return $widths;
    }

    public function title(): string
    {
        // Nama sheet = nama group, max 31 karakter (limit Excel)
        return substr($this->group->nama_group, 0, 31);
    }
}
