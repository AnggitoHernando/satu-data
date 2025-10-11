<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class JenisData extends Model
{
    use HasFactory;

    protected $table = 'jenis_data'; //
    public function seksi()
    {
        return $this->belongsTo(Seksi::class, 'seksi_id');
    }
}
