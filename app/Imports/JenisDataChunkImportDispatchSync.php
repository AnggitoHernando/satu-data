<?php

namespace App\Imports;

use App\Models\JenisData;
use App\Models\JenisDataFields;
use App\Models\JenisDataRecords;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class JenisDataChunkImportDispatchSync implements ToCollection, WithHeadingRow, WithChunkReading
{
    protected $jenisData;

    public function __construct(JenisData $jenisData)
    {
        $this->jenisData = $jenisData;
    }

    public function collection(Collection $rows)
    {
        // Ambil header hanya sekali
        // Log::info("[ChunkImport] Collection started. Row count: " . $rows->count());
        if ($this->jenisData->fields()->count() === 0 && $rows->isNotEmpty()) {
            $headers = array_keys($rows->first()->toArray());
            foreach ($headers as $i => $header) {
                $fields[] = [
                    'jenis_data_id' => $this->jenisData->id,
                    'nama_field' => $header,
                    'urutan' => $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            JenisDataFields::insert($fields);
            // Log::info("[ChunkImport] Fields inserted: " . count($fields));
        }

        // Simpan tiap baris
        foreach ($rows as $row) {
            $records[] = [
                'jenis_data_id' => $this->jenisData->id,
                'data_json' => json_encode($row->toArray()),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        if (!empty($records)) {
            JenisDataRecords::insert($records);
            // Log::info("[ChunkImport] Records inserted: " . count($records));
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
