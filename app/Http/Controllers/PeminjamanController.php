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
    public function tampilAllPeminjaman()
    {
        $id_user = Auth::user()->id;
        $peminjaman = Peminjaman::where('UserID', $id_user)->with(['users', 'buku'])->get();
        return response(['Status' => 200, 'Message' => 'Berhasil Menampilkan All Peminjaman' , 'Data' => PeminjamanRecource::collection($peminjaman) ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function addPeminjaman(AddPeminjamanRequests $request)
    {
        $data = $request->validated();
        $data['KodePeminjaman'] = Carbon::now()->tz('Asia/Jakarta')->format('Y') . strtoupper(Str::random(3)) . Carbon::now()->tz('Asia/Jakarta')->format('d').Auth()->user()->id;
        $data['TanggalPinjam'] = Carbon::now()->tz('Asia/Jakarta')->format('Y-m-d');
        $data['Deadline'] = Carbon::now()->tz('Asia/Jakarta')->addWeek()->format('Y-m-d');
        $data['UserID'] = Auth::user()->id;
        $data['Status'] = 'Dipinjam';

        $queryData = Peminjaman::create($data);
        return Response(['Status' => 200, 'Message' => 'Berhasil Menambahkan Peminjaman', 'Data' => new PeminjamanRecource($queryData)], 200); 
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
