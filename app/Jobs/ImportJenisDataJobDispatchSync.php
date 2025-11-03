<?php

namespace App\Jobs;

use App\Imports\JenisDataChunkImportDispatchSync;
use App\Models\JenisData;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ImportJenisDataJobDispatchSync
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

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
        // Log::info('Job started');
        $jenisData = JenisData::findOrFail($this->jenisDataId);
        if (!$jenisData) {
            Log::error("[Job] JenisData not found for ID: {$this->jenisDataId}");
            return;
        }

        // Log::info("[Job] JenisData loaded: ", $jenisData->toArray());
        $jenisData->update(['status_upload' => 'processing']);

        try {
            $filePath = storage_path('app/public/' . $jenisData->file_path);

            Excel::import(new JenisDataChunkImportDispatchSync($jenisData), $filePath);
            DB::table('jobs')
                ->where('payload', 'like', "%i:$jenisData->id%")
                ->delete();
            $jenisData->update(['status_upload' => 'success']);
            // Log::info("[Job] Excel import finished successfully for ID: {$this->jenisDataId}");
        } catch (Exception $e) {
            $jenisData->update([
                'status_upload' => 'failed',
                'error_message_upload' => $e->getMessage(),
            ]);
            // Log::error("[Job] Excel import failed: " . $e->getMessage());
        }
        // Log::info('Job Done');
    }
}
