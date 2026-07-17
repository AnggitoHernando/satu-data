<?php

namespace App\Imports;

use App\Models\GroupKategori;
use App\Models\IsiStatistik;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Facades\Excel;

class StatistikMultiSheetImport implements WithMultipleSheets
{
    protected int $kategoriId;
    public array $results = []; // ['sheet' => nama, 'inserted' => n, 'updated' => n, 'errors' => []]
    public array $sheetImports = [];

    public function __construct(int $kategoriId)
    {
        $this->kategoriId = $kategoriId;
    }

    public function sheets(): array
    {
        // Ambil semua group milik kategori
        $groups = GroupKategori::where('kategori_data_id', $this->kategoriId)
            ->get(['id', 'nama_group']);

        // Map nama_group => sheet import instance
        $sheets = [];
        foreach ($groups as $group) {
            $sheetName = substr($group->nama_group, 0, 31);
            $import    = new StatistikSingleSheetImport($group);
            $this->sheetImports[$sheetName] = $import;
            $sheets[$sheetName] = $import;
        }

        return $sheets;
    }

    public function getResults(): array
    {
        return array_map(function ($sheetName, $import) {
            return [
                'sheet'    => $sheetName,
                'inserted' => $import->inserted,
                'updated'  => $import->updated,
                'errors'   => $import->errors,
            ];
        }, array_keys($this->sheetImports), $this->sheetImports);
    }
}


class StatistikSingleSheetImport implements ToCollection
{
    protected GroupKategori $group;
    public int $inserted  = 0;
    public int $updated   = 0;
    public array $errors  = [];

    public function __construct(GroupKategori $group)
    {
        $this->group = $group;
    }

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) return;

        // Row pertama harus berisi __META__
        $metaRow = $rows->first()->toArray();

        if (($metaRow[0] ?? '') !== '__META__') {
            $this->errors[] = "Sheet '{$this->group->nama_group}': file tidak sesuai template. Baris meta tidak ditemukan.";
            return;
        }

        // Ambil item_id dari meta row (kolom 1 dst)
        $itemIds = array_slice($metaRow, 1);

        // Validasi item_id ada di database dan milik group ini
        $validItemIds = $this->group->items()->pluck('id')->toArray();
        foreach ($itemIds as $itemId) {
            if ($itemId && !in_array($itemId, $validItemIds)) {
                $this->errors[] = "Sheet '{$this->group->nama_group}': item ID {$itemId} tidak valid atau bukan milik group ini.";
                return;
            }
        }

        // Row kedua = header (skip)
        // Row ketiga dst = data
        $dataRows = $rows->slice(2);

        foreach ($dataRows as $rowIndex => $row) {
            $rowArray = $row->toArray();
            $tahun    = $rowArray[0] ?? null;

            if (!$tahun || !is_numeric($tahun)) {
                $this->errors[] = "Sheet '{$this->group->nama_group}' baris " . ($rowIndex + 3) . ": kolom tahun tidak valid.";
                continue;
            }

            foreach ($itemIds as $colIndex => $itemId) {
                if (!$itemId) continue;

                $value = $rowArray[$colIndex + 1] ?? null;
                if ($value === null || $value === '') continue;

                if (!is_numeric($value)) {
                    $this->errors[] = "Sheet '{$this->group->nama_group}' baris " . ($rowIndex + 3) . " kolom " . ($colIndex + 2) . ": nilai harus angka.";
                    continue;
                }

                $existing = IsiStatistik::where('group_kategori_item_id', $itemId)
                    ->where('tahun', (int) $tahun)
                    ->first();

                if ($existing) {
                    $existing->update(['value' => (float) $value]);
                    $this->updated++;
                } else {
                    IsiStatistik::create([
                        'group_kategori_item_id' => $itemId,
                        'value'                  => (float) $value,
                        'tahun'                  => (int) $tahun,
                    ]);
                    $this->inserted++;
                }
            }
        }
    }
}
