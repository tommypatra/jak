<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
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
            'deskripsi' => $this->deskripsi,
            'jenis_file' => $this->jenis_file,
            'waktu' => $this->waktu,
            'jenis_konten_id' => $this->jeniskonten->id,
            'jenis_konten_nama' => $this->jeniskonten->nama,
            'jenis_konten_slug' => $this->jeniskonten->slug,
            'jenis_konten_deskripsi' => $this->jeniskonten->deskripsi,
            'jenis_konten_kategori' => $this->jeniskonten->kategori,

            'cover' => ($this->cover) ? Storage::url($this->cover) : null,
            'path' => Storage::url($this->path),
            'judul' => $this->judul,

            'jumlah_akses' => $this->jumlah_akses,
            'komentar_count' => $this->komentar_count,
            'likedislike_count' => $this->likedislike_count,
            'slug' => $this->slug,
            'ukuran' => $this->ukuran,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),

            'publikasi' => $this->whenLoaded('publikasi') ? [
                'id' => $this->publikasi->id,
                'is_publikasi' => $this->publikasi->is_publikasi,
                'file_id' => $this->publikasi->konten_id,
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
