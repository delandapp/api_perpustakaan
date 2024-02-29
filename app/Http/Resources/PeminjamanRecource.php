<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeminjamanRecource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'PeminjamanID' => $this->PeminjamanID ?? $this->id,
            'kode_peminjaman' => $this->KodePeminjaman,
            'status' => $this->Status,
            'username' => $this->whenLoaded('users', fn() => $this->users->Username),
            'email' => $this->whenLoaded('users', fn() => $this->users->Email),
            'tanggal_pinjam' => $this->TanggalPinjam,
            'tanggal_kembali' => $this->TanggalKembali == null ? 'Belum dikembalikan' : $this->TanggalKembali,
            'Deadline' => $this->Deadline,
            'judul_buku' => $this->whenLoaded('users', fn() => $this->buku->Judul),
            'penulis_buku' => $this->whenLoaded('users', fn() => $this->buku->Penulis),
            'penerbit_buku' => $this->whenLoaded('users', fn() => $this->buku->Penerbit),
            'tahun_buku' => $this->whenLoaded('users', fn() => $this->buku->TahunTerbit),
        ];
    }
}
