<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriData extends Model
{
    protected $fillable = [
        "seksi_id",
        "nama_kategori"
    ];

    public function seksi()
    {
        return $this->belongsTo(Seksi::class, 'seksi_id');
    }
}
