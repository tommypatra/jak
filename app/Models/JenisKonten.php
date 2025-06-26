<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKonten extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    protected static function boot()
    {
        parent::boot();
        //function dipakai, atur logika atau mendefinisikan nilai sebelum simpan data
        static::creating(function ($dt) {
            $user_id = auth()->check() ? auth()->id() : 1;
            $dt->user_id = $user_id;
        });

        static::updating(function ($dt) {
            $user_id = auth()->check() ? auth()->id() : 1;
            $dt->user_id = $user_id;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function konten()
    {
        return $this->hasMany(Konten::class);
    }

    public function publikasi()
    {
        return $this->hasMany(Publikasi::class);
    }

    public function file()
    {
        return $this->hasMany(File::class);
    }
}
