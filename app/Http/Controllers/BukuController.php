<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Buku;
use Illuminate\Http\Request;
use League\Flysystem\Filesystem;
use App\Http\Resources\BukuRecource;
use PhpParser\Node\Expr\Cast\Double;
use App\Http\Requests\AddBukuRequests;
use App\Models\KategoriBuku;
use App\Models\KategoriBukuRelasi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;
use League\Flysystem\Local\LocalFilesystemAdapter;

class BukuController extends Controller
{
    public function getBukuPopuler()
    {
        $data_buku = Buku::whereHas('ulasan')->with(['ulasan', 'kategori'])->take(8)->get();
        $data_buku = $data_buku->filter(function ($value) {
            return $value->ulasan->sum('Rating') / $value->ulasan->count() >= 4.5;
        })->values();

        $data_buku = $data_buku->map(function ($item) {
            return [
                'BukuID' => $item->BukuID,
                'Deskripsi' => $item->Deskripsi,
                'CoverBuku' => env('PUBLIC_IMAGE_URL') . $item->CoverBuku,
                'Judul' => $item->Judul,
                'Penulis' => $item->Penulis,
                'Penerbit' => $item->Penerbit,
                'TahunTerbit' => $item->TahunTerbit,
                'JumlahHalaman' => $item->JumlahHalaman,
                'Rating' =>  floatval($item->ulasan->count() == 0 ? 0 : $item->ulasan()->sum('rating') / $item->ulasan->count()),
                'Total_ulasan' => $item->ulasan->count(),
                'JumlahRating' => intval($item->ulasan()->sum('rating')),
                'JumlahPeminjam' => intval($item->peminjaman()->count()),
                'Kategori' => $item->kategori->map(function ($item) {
                    return $item->NamaKategori;
                })
            ];
        });
        return response(['Status' => 200, 'Message' => 'Berhasil Menampilkan All Buku', 'data' => $data_buku], 200);
    }

    public function tampilAllBukuNew()
    {
        $data_buku = Buku::orderBy('created_at', 'desc')->take(20)->get();
        $data_buku = $data_buku->map(function ($item) {
            return [
                'BukuID' => $item->BukuID,
                'CoverBuku' => env('PUBLIC_IMAGE_URL') . $item->CoverBuku,
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
        return response(['Status' => 200, 'Message' => 'Berhasil Menampilkan All Buku', 'data' => $data_buku], 200);
    }

    public function tampilAllBuku()
    {
        $data_buku = KategoriBuku::with(['buku'])->get();
        $data_buku = $data_buku->map(function ($item) {
            return [
                'KategoriBuku' => $item->NamaKategori,
                'Buku' => $item->buku->map(function ($item) {
                    return [
                        'BukuID' => $item->BukuID,
                        'CoverBuku' => env('PUBLIC_IMAGE_URL') . $item->CoverBuku,
                        'Judul' => $item->Judul,
                        'Penulis' => $item->Penulis,
                        'Penerbit' => $item->Penerbit,
                        'TahunTerbit' => $item->TahunTerbit,
                        'JumlahHalaman' => $item->JumlahHalaman,
                        'Rating' =>  floatval($item->ulasan->count() == 0 ? 0 : $item->ulasan()->sum('rating') / $item->ulasan->count()),
                        'Total_ulasan' => $item->ulasan->count(),
                        'JumlahRating' => intval($item->ulasan()->sum('rating')),
                        'JumlahPeminjam' => intval($item->peminjaman()->count())
                    ];
                })
            ];
        });
        // $data_buku = Buku::with(['ulasan'])->get();
        // $data_buku = $data_buku->map(function ($item) {
        //     return [
        //         'BukuID' => $item->BukuID,
        //         'CoverBuku' => env('PUBLIC_IMAGE_URL') . $item->CoverBuku,
        //         'judul_buku' => $item->Judul,
        //         'penulis_buku' => $item->Penulis,
        //         'penerbit_buku' => $item->Penerbit,
        //         'tahun_terbit' => $item->TahunTerbit,
        //         'jumlah_halaman' => $item->JumlahHalaman,
        //         'Rating' =>  floatval($item->ulasan->count() == 0 ? 0 : $item->ulasan()->sum('rating') / $item->ulasan->count()),
        //         'Total_ulasan' => $item->ulasan->count(),
        //         'JumlahRating' => intval($item->ulasan()->sum('rating')),
        //         'JumlahPeminjam' => intval($item->peminjaman()->count())
        //     ];
        // });
        return response(['Status' => 200, 'Message' => 'Berhasil Menampilkan All Buku', 'data' => $data_buku], 200);
    }

    public function addBuku(AddBukuRequests $request)
    {
        $data_buku = $request->validated();

        if ($request->file('CoverBuku') == null) {
            return response(['Message' => 'Buku gagal diupload', 'Status' => 400], 400);
        }
        // Generate nama file unik
        $filename = $data_buku['Judul'] . '.' . $request->file('CoverBuku')->getClientOriginalExtension();

        $data_buku['CoverBuku'] = $filename;

        // Simpan image di direktori private
        $path = $request->file('CoverBuku')->storeAs('public/images/coverbook/', $filename);

        // Simpan Database
        $buku = Buku::create($data_buku);

        // Kategori Collect
        $kategori = collect($request['id_kategori']);
        // Create Kategori Relasi
        $kategori_buku = $kategori->map(function ($item) use ($buku) {
            KategoriBukuRelasi::create([
                'BukuID' => $buku['BukuID'],
                'KategoriID' => $item[0]
            ]);
        });

        // Get Database Return
        $data_buku = Buku::where('BukuID', $buku['BukuID'])->with(['kategori'])->first();

        // Generate URL image yang aman
        $url = Storage::url($path);

        // Ubah Array CoverBuku
        $data_buku['CoverBuku'] = env('PUBLIC_IMAGE_URL') . $filename;

        // Kirim URL image ke frontend
        return response(['Status' => 200, 'Message' => 'Berhasil Create Buku', 'Data' => new BukuRecource($data_buku)], 200);
    }

    //     private function generateSignedUrl(Buku $buku)
    // {
    //     // Generate URL dengan timestamp dan signature
    //   ?  $path = 'images/private/' . $buku->CoverBuku;
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
