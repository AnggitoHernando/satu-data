<?php

namespace App\Jobs;

use App\Imports\JenisDataChunkImport;
use App\Models\JenisData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\Log;

class ImportJenisDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jenisDataId;

    /**
     * Buat konstruktor untuk menyimpan data yang dikirim dari Controller.
     */
    public function __construct($jenisDataId)
    {
        $this->jenisDataId = $jenisDataId;
        // Log::info("[Job] Constructed with ID: {$jenisDataId}");
    }

    /**
     * Jalankan Job-nya.
     */
    public function handle(): void
    {
        $jenisData = JenisData::findOrFail($this->jenisDataId);
        if (!$jenisData) {
            Log::error("[Job] JenisData not found for ID: {$this->jenisDataId}");
            return;
        }

        // Log::info("[Job] JenisData loaded: ", $jenisData->toArray());
        $jenisData->update(['status_upload' => 'processing']);

        try {
            $filePath = storage_path('app/public/' . $jenisData->file_path);

            Excel::import(new JenisDataChunkImport($jenisData), $filePath);

            $jenisData->update(['status_upload' => 'success']);
            // Log::info("[Job] Excel import finished successfully for ID: {$this->jenisDataId}");
        } catch (Exception $e) {
            $jenisData->update([
                'status_upload' => 'failed',
                'error_message_upload' => $e->getMessage(),
            ]);
            Log::error("[Job] Excel import failed: " . $e->getMessage());
        }
    }
}
