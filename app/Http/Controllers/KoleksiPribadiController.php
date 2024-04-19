<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddKoleksiRequests;
use App\Models\KoleksiPribadi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KoleksiPribadiController extends Controller
{
    public function addKoleksi(AddKoleksiRequests $request)
    {
        $data = $request->validated();
        $data_koleksicheck = KoleksiPribadi::where('UserID', $data['UserID'])->where('BukuID', $data['BukuID'])->first();
        if ($data_koleksicheck != null) {
            return Response(['Status' => 400, 'Message' => 'Koleksi Sudah Ada'], 400);
        }
        $data_koleksi = KoleksiPribadi::create($data);
        return Response(['Status' => 201, 'Message' => 'Berhasil Add Koleksi', 'data' => $data_koleksi], 201);
    }

    public function deleteKoleksi($id) {
        $idUser = Auth::user()->id;
        $data = KoleksiPribadi::where('UserID', $idUser)->where('BukuID', $id)->delete();
        if ($data == 0) {
            return Response(['Status' => 404, 'Message' => 'Koleksi Tidak Ditemukan'], 404);
        }
        return Response(['Status' => 200, 'Message' => 'Berhasil Delete Koleksi'], 200);
    }

    public function tampilUlasanBy($id)
    {
        $data_koleksi = User::where('id', $id)->with(['koleksipribadi'])->first();
        $data_koleksi =$data_koleksi->koleksipribadi->map(function ($item) {
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
                        'JumlahPeminjam' => intval($item->peminjaman()->count()),
                        'Kategori' => $item->kategori->map(function ($item) { 
                            return $item->NamaKategori;
                        })
                    ];
                });
        return Response(['Status' => 200, 'Message' => 'Berhasil Get Koleksi', 'data' => $data_koleksi], 200);
    }
}
