<?php

namespace App\Exports;

use App\Models\GroupKategori;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

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

        // Row 1: Item Id
        $row1 = ['__META__', ...$itemIds];

        $row2 = ['Nama Data', $this->group->kategoriData->nama_kategori ?? ''];

        $row3 = ['Group By', $this->group->nama_group];

        // Row 4: META (tersembunyi) — penanda item_id
        $row4 = ['', '', ''];

        // Row 5: Header kolom yang user lihat
        $row5 = ['Tahun', ...$itemNames];

        // Row 6 dst: baris kosong untuk 3 tahun terakhir
        $tahunSekarang = now()->year;
        $dataRows = array_map(
            fn($tahun) => array_fill(0, count($this->items) + 1, '') + [0 => $tahun],
            range($tahunSekarang, $tahunSekarang - 2)
        );

        return [$row1, $row2, $row3, $row4, $row5, ...$dataRows];
    }

    public function styles(Worksheet $sheet): array
    {
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
            count($this->items) + 1
        );

        $sheet->getRowDimension(1)->setVisible(false);

        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A3')->getFont()->setBold(true);

        $sheet->getStyle("A5:{$lastCol}5")->getFont()->setBold(true);

        $sheet->getStyle("A5:{$lastCol}5")->getBorders()->getAllBorders()->setBorderStyle(
            \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
        );

        $sheet->getStyle("A5:{$lastCol}5")->getAlignment()->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );
        $sheet->getStyle("A5:{$lastCol}5")->getAlignment()->setVertical(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        );

        return [];
    }

    public function columnWidths(): array
    {
        $widths = ['A' => 16];
        foreach (range(2, count($this->items) + 1) as $i) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            $widths[$col] = 16;
        }
        return $widths;
    }

    public function title(): string
    {
        return substr($this->group->nama_group, 0, 31);
    }
}
