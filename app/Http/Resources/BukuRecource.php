<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuRecource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'BukuID' => $this->BukuID ?? $this->id,
            'judul_buku' => $this->Judul,
            'penulis_buku' => $this->Penulis,
            'penerbit_buku' => $this->Penerbit,
            'tahun_terbit' => $this->TahunTerbit,
            'jumlah_halaman' => $this->JumlahHalaman,
            'rating' => $this->Rating
        ];
    }
}
