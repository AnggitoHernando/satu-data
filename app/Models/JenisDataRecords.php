<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisDataRecords extends Model
{
    protected $table = 'jenis_data_records';
    protected $fillable = [
        'jenis_data_id',
        'data'
    ];
    protected $casts = [
        'data' => 'array',
    ];
    public function jenis_data()
    {
        return $this->belongsTo(JenisData::class, 'jenis_data_id');
    }
}
