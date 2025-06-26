<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class PengaturanWebResource extends JsonResource
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
            'alamat' => $this->alamat,
            'confirm_komentar' => $this->confirm_komentar,
            'confirm_file' => $this->confirm_file,
            'confirm_konten' => $this->confirm_konten,
            'icon' => $this->icon ? Storage::url($this->icon) : 'images/logo.png',
            'logo' => $this->logo ? Storage::url($this->logo) : 'images/logo.png',

            'deskripsi' => $this->deskripsi,
            'email' => $this->email,
            'fb' => $this->fb,
            'helpdesk' => $this->helpdesk,
            'tiktok' => $this->tiktok,
            'ig' => $this->ig,
            'keywords' => $this->keywords,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'nama' => $this->nama,
            'nomor' => $this->nomor,
            'twitter' => $this->twitter,
            'youtube' => $this->youtube,

            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,

            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
