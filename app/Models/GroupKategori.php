<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupKategori extends Model
{
    protected $table = "group_kategoris";
    protected $fillable = ["kategori_data_id", "nama_group_kategori"];

    public function kategoriData()
    {
        return $this->belongsTo(KategoriData::class, "kategori_data_id");
    }

    public function isiStatistiks()
    {
        return $this->hasMany(IsiStatistik::class, "group_kategori_id");
    }
}
