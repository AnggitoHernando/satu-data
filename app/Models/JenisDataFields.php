<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisDataFields extends Model
{
    use HasFactory;

    protected $table = 'jenis_data_fields'; //
    public function jenis_data()
    {
        return $this->belongsTo(JenisData::class, 'jenis_data_id');
    }
}
