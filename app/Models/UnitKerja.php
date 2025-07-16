<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function profil()
    {
        return $this->hasOne(Profil::class);
    }

    public function unitKerjaParent()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function unitKerjaChildren()
    {
        return $this->hasMany(UnitKerja::class, 'unit_kerja_id');
    }
}
