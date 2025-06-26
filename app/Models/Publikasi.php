<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publikasi extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    protected static function boot()
    {
        parent::boot();
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

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function galeri()
    {
        return $this->belongsTo(Galeri::class);
    }

    public function konten()
    {
        return $this->belongsTo(Konten::class);
    }
}
