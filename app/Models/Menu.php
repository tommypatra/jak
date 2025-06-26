<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    protected static function boot()
    {
        parent::boot();

        //function dipakai, atur logika atau mendefinisikan nilai sebelum simpan data
        static::creating(function ($dt) {
            $menu_id = ($dt->menu_id > 0) ? $dt->menu_id : null;
            $maxUrut = Menu::where('menu_id', $menu_id)->max('urut');
            $dt->urut = $maxUrut + 1;
            // $menu->user_id = Auth::id();
            $user_id = auth()->check() ? auth()->id() : 1;
            $dt->user_id = $user_id;
            $dt->menu_id = $menu_id;
        });

        static::updating(function ($dt) {
            $user_id = auth()->check() ? auth()->id() : 1;
            $dt->user_id = $user_id;
            $menu_id = ($dt->menu_id > 0) ? $dt->menu_id : null;
            $dt->menu_id = $menu_id;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
