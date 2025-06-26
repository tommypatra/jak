<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function menu()
    {
        return $this->hasMany(Menu::class);
    }

    public function grup()
    {
        return $this->hasMany(Grup::class);
    }

    public function aturgrup()
    {
        return $this->hasMany(AturGrup::class);
    }

    public function publikasi()
    {
        return $this->hasMany(Publikasi::class);
    }

    public function jeniskonten()
    {
        return $this->hasMany(JenisKonten::class);
    }

    public function konten()
    {
        return $this->hasMany(Konten::class);
    }

    public function likedislike()
    {
        return $this->hasMany(LikeDislike::class);
    }

    public function komentar()
    {
        return $this->hasMany(Komentar::class);
    }

    public function galeri()
    {
        return $this->hasMany(Galeri::class);
    }

    public function file()
    {
        return $this->hasMany(File::class);
    }

    public function shortlink()
    {
        return $this->hasMany(ShortLink::class);
    }

    public function kotaksaran()
    {
        return $this->hasMany(KotakSaran::class);
    }

    public function profil()
    {
        return $this->hasMany(Profil::class);
    }

    public function pengaturanweb()
    {
        return $this->hasMany(PengaturanWeb::class);
    }

    public function htmlcode()
    {
        return $this->hasMany(HtmlCode::class);
    }

    public function slideshow()
    {
        return $this->hasMany(SlideShow::class);
    }
}
