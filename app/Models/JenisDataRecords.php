<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisDataRecords extends Model
{
    public function jenis_data()
    {
        return $this->belongsTo(JenisData::class, 'jenis_data_id');
    }
}
