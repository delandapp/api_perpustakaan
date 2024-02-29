<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUlasanRequests;
use App\Http\Resources\UlasanRecource;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class UlasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function tampilAllUlasan()
    {
        $data = Ulasan::with(['users','buku'])->get();
        return Response(['Status' => 200, 'Message' => 'Berhasil Menampilkan Ulasan', 'Data' => UlasanRecource::collection($data)], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function addUlasan(AddUlasanRequests $request)
    {
        $data = $request->validated();

        if($data['Rating'] > 5 && $data['Rating'] < 0) {
            return Response(['Status' => 403, 'Message' => 'Masukan rating lebih dari 0 dan kurang dari 5'], 403);
        }

        $data['UserID'] = Auth::user()->id;
        $query_ulasan = Ulasan::create($data);
        return Response(['Status' => 201, 'Message' => 'Berhasil Menambahkan Ulasan', 'Data' => new UlasanRecource($query_ulasan)], 201);
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
    public function show(Ulasan $ulasan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ulasan $ulasan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ulasan $ulasan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ulasan $ulasan)
    {
        //
    }
}
