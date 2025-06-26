<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortLink extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($dt) {
            $dt->slug = ($dt->slug) ? $dt->slug : generateSlug($dt->nama, $dt->waktu);
            $user_id = auth()->check() ? auth()->id() : 1;
            $dt->user_id = $user_id;
        });
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
