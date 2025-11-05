<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seksi extends Model
{
    use HasFactory;

    protected $table = 'seksi';
    public function jenis_data()
    {
        return $this->hasMany(JenisData::class, 'seksi_id');
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user_seksi')
            ->withPivot('role')
            ->withTimestamps();
    }
}
