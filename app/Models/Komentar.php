<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    protected static function boot()
    {
        parent::boot();
        static::updating(function ($dt) {
            $user_id = auth()->check() ? auth()->id() : 1;
            $dt->user_id = $user_id;
        });
    }

    public function setIsPublikasiAttribute($value)
    {
        if (auth()->check()) {
            if (!is_admin() && !is_editor()) {
                // throw new \Exception("tidak bisa mengubah kolom 'is_publikasi', khusus admin.");
                abort(response()->json(['message' => "Tidak bisa mengubah kolom 'is_publikasi', khusus admin."], 403));
            }
        }
        $this->attributes['is_publikasi'] = $value;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function konten()
    {
        return $this->belongsTo(Konten::class, 'konten_id');
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
