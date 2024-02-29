<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UlasanRecource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'UlasanID' => $this->UlasanID ?? $this->id,
            'Username' => $this->whenLoaded('users', fn () => $this->users->Username),
            'judul_buku' => $this->whenLoaded('buku', fn () => $this->buku->Judul),
            'penulis_buku' => $this->whenLoaded('buku', fn () => $this->buku->Penulis),
            'penerbit_buku' => $this->whenLoaded('buku', fn () => $this->buku->Penerbit),
            'rating' => $this->Rating,
            'ulasan' => $this->Ulasan
        ];
    }
}
