<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class KontenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'judul' => $this->judul,
            'waktu' => $this->waktu,
            'pembuka' => ambilKata($this->isi, 15),
            'thumbnail' => $this->thumbnail ? Storage::url($this->thumbnail) : null,
            'slug' => $this->slug,
            'isi' => $this->isi,
            'jumlah_akses' => $this->jumlah_akses,

            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),

            'komentar_count' => $this->komentar_count,
            'likedislike_count' => $this->likedislike_count,

            'jenis_konten_id' => $this->jeniskonten->id,
            'jenis_konten_nama' => $this->jeniskonten->nama,
            'jenis_konten_slug' => $this->jeniskonten->slug,
            'jenis_konten_deskripsi' => $this->jeniskonten->deskripsi,
            'jenis_konten_kategori' => $this->jeniskonten->kategori,

            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,

            'publikasi' => $this->whenLoaded('publikasi') ? [
                'id' => $this->publikasi->id,
                'is_publikasi' => $this->publikasi->is_publikasi,
                'konten_id' => $this->publikasi->konten_id,
                'catatan' => $this->publikasi->catatan,
                'created_at' => $this->publikasi->created_at->toDateTimeString(),
                'updated_at' => $this->publikasi->updated_at->toDateTimeString(),
                'user_id' => $this->publikasi->user->id,
                'user_name' => $this->publikasi->user->name,
                'user_email' => $this->publikasi->user->email,
            ] : null
        ];
    }
}
