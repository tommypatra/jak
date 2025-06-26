<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konten extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    protected static function boot()
    {
        parent::boot();
        //function dipakai, atur logika atau mendefinisikan nilai sebelum simpan data
        static::creating(function ($dt) {

            $dt->slug = ($dt->user_id) ? $dt->slug : generateSlug($dt->judul, $dt->waktu);

            $user_id = auth()->check() ? auth()->id() : 1;
            $dt->user_id = $user_id;
        });

        // static::updating(function ($dt) {
        // $user_id = auth()->check() ? auth()->id() : 1;
        // $dt->user_id = $user_id;
        // });

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenisKonten()
    {
        return $this->belongsTo(JenisKonten::class);
    }

    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'konten_id');
    }

    public function publikasi()
    {
        return $this->hasOne(Publikasi::class, 'konten_id');
    }

    public function likedislike()
    {
        return $this->hasMany(LikeDislike::class, 'konten_id');
    }
}
