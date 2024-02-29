<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Buku;
use Illuminate\Http\Request;
use League\Flysystem\Filesystem;
use App\Http\Resources\BukuRecource;
use PhpParser\Node\Expr\Cast\Double;
use App\Http\Requests\AddBukuRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;
use League\Flysystem\Local\LocalFilesystemAdapter;

class BukuController extends Controller
{
    public function tampilAllBuku()
    {
        $data_buku = Buku::with(['ulasan'])->get();
        $data_buku = $data_buku->map(function ($item) {
            return [
                'BukuID' => $item->BukuID,
                'judul_buku' => $item->Judul,
                'penulis_buku' => $item->Penulis,
                'penerbit_buku' => $item->Penerbit,
                'tahun_terbit' => $item->TahunTerbit,
                'jumlah_halaman' => $item->JumlahHalaman,
                'Rating' =>  floatval($item->ulasan->count() == 0 ? 0 : $item->ulasan()->sum('rating') / $item->ulasan->count()),
                'Total_ulasan' => $item->ulasan->count(),
                'JumlahRating' => intval($item->ulasan()->sum('rating')),
                'JumlahPeminjam' => intval($item->peminjaman()->count())
            ];
        });
        return response(['Status' => 200, 'Message' => 'Berhasil Menampilkan All Buku', 'Data' => $data_buku], 200);
    }

    public function addBuku(AddBukuRequests $request)
    {
        $data_buku = $request->validated();

        if ($request->file('CoverBuku') == null) {
            return response(['Message' => 'Buku gagal diupload', 'Status' => 400], 400);
        }
        // Generate nama file unik
        $filename = $data_buku['Judul'] . '.' . $request->file('CoverBuku')->getClientOriginalExtension();

        // Simpan image di direktori private
        $path = $request->file('CoverBuku')->storeAs('public/images/coverbook/', $filename);

        // Simpan Database
        $buku = Buku::create($data_buku);

        // Generate URL image yang aman
        $url = Storage::url($path);

        // Ubah Array CoverBuku
        $data_buku['CoverBuku'] = env('PUBLIC_IMAGE_URL') . $filename;

        // Kirim URL image ke frontend
        return response(['Status' => 200, 'Message' => 'Berhasil Create Buku', 'Data' => $data_buku],200);
    }

//     private function generateSignedUrl(Buku $buku)
// {
//     // Generate URL dengan timestamp dan signature
//     $path = 'images/private/' . $buku->CoverBuku;
//     $expiration = Carbon::now()->addHour(5);

//     // Ambil path dengan library
//     $adapter = new LocalFilesystemAdapter(__DIR__ . '/storage/images/private');
//     $filesystem = new Filesystem($adapter);
//     $url = $filesystem->temporaryUrl($path, $expiration);

//     // Hitung signature
//     $signature = hash_hmac('sha256', $url, config('app.key'));

//     // Append signature ke URL
//     return $url . '?signature=' . $signature;
// }
}
