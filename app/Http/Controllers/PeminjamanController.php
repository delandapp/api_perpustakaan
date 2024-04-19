<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Nette\Utils\Random;
use App\Models\Peminjaman;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PeminjamanRecource;
use App\Http\Requests\AddPeminjamanRequests;
use GuzzleHttp\Psr7\Response;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function destroyPinjam($id) {
        $idUser = Auth::user()->id;
        $data = Peminjaman::where('UserID', $idUser)->where('PeminjamanID', $id)->update([
            'Tampil_User' => 'no'
        ]);
        if ($data == 0) {
            return Response(['Status' => 404, 'Message' => 'History Tidak Ditemukan'], 404);
        }
        return Response(['Status' => 200, 'Message' => 'Berhasil Delete History'], 200);
    }

    public function tampilAllPeminjaman()
    {
        $id_user = Auth::user()->id;
        $peminjaman = Peminjaman::where('UserID', $id_user)->where('Tampil_User', 'yes')->with(['users', 'buku'])->orderBy('created_at', 'asc')->get();
        return response(['Status' => 200, 'Message' => 'Berhasil Menampilkan All Peminjaman', 'data' => PeminjamanRecource::collection($peminjaman)], 200);
    }

    public function tampilPeminjaman($id) {
        $id_user = Auth::user()->id;
        $peminjaman = Peminjaman::where('UserID', $id_user)->where('PeminjamanID', $id)->with(['users', 'buku'])->first();
        if($peminjaman == null) {
            return Response(['Status' => 200, 'Message' => 'Buku Not Found', 'data' => []], 200);
        }
        return Response(['Status' => 200, 'Message' => 'Berhasil Menampilkan Peminjaman', 'data' => new PeminjamanRecource($peminjaman)], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function addPeminjaman(AddPeminjamanRequests $request)
    {
        $data = $request->validated();
        $data['UserID'] = Auth::user()->id;
        $cekStatus = Peminjaman::where('BukuID', $data['BukuID'])->where('UserID', $data['UserID'])->latest('TanggalPinjam')->first();
        if ($cekStatus) {
            if ($cekStatus->Status == 'Dipinjam') {
                return Response(['Status' => 200, 'Message' => 'Buku Masih Di Pinjam', 'data' => new PeminjamanRecource($cekStatus)], 200);
            }
        }
        $data['KodePeminjaman'] = Carbon::now()->tz('Asia/Jakarta')->format('Y') . strtoupper(Str::random(3)) . Carbon::now()->tz('Asia/Jakarta')->format('d') . Auth()->user()->id;
        $data['TanggalPinjam'] = Carbon::now()->tz('Asia/Jakarta')->format('Y-m-d');
        $data['Deadline'] = Carbon::now()->tz('Asia/Jakarta')->addWeek()->format('Y-m-d');
        $data['Status'] = 'Dipinjam';

        $queryData = Peminjaman::create($data);
        return Response(['Status' => 200, 'Message' => 'Berhasil Menambahkan Peminjaman', 'data' => new PeminjamanRecource($queryData)], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peminjaman $peminjaman)
    {
        //
    }
}
