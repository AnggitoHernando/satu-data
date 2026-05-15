<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupKategoriItem extends Model
{
    protected $fillable = [
        'group_kategori_id',
        'nama_item',
    ];

    public function groupKategori()
    {
        return $this->belongsTo(GroupKategori::class, 'group_kategori_id');
    }

    public function isiStatistik()
    {
        return $this->hasMany(IsiStatistik::class, 'group_kategori_item_id');
    }
}
