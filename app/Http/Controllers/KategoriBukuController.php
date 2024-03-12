<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddKategoriRequests;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;

class KategoriBukuController extends Controller
{
    public function createKategori(AddKategoriRequests $request) {
        $data = $request->validated();
        $data_kategori = KategoriBuku::create($data);
        return Response(['Status' => 201, 'Message' => 'Berhasil Add Kategori', 'data' => $data_kategori], 201);
    }

    public function getAllKategori() {
        $data_kategori = KategoriBuku::all();
        return Response(['Status' => 200, 'Message' => 'Berhasil Get Kategori Buku', 'data' => $data_kategori], 200);
    }
}
