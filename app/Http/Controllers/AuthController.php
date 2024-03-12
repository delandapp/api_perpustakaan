<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequests;
use App\Http\Requests\UserRegistrasiRequest;
use App\Http\Resources\UserRecource;
use App\Models\DetailsUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(UserLoginRequests $request)
    {
        $data = $request->validated();
        $user = User::where('Email', $data['email'])->first();

        if (!$user || ! Hash::check($request->password, $user->Password)) {
            return response([
                "status" => 400, 
                "message" => "Email atau password tidak cocok"], 400);
        }

        $user['token'] = $user->createToken('user login')->plainTextToken;

        return response([
            "status" => 200, 
            "message" => "Login Berhasil", 
            'data' => new UserRecource($user)], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function registrasi(UserRegistrasiRequest $request)
    {
        $data = $request->validated();
        $data_email = explode('@', $data['email']);
        if ($data_email[1] != "smk.belajar.id") {
           return response([
                "status" => 400, 
                "message" => "Email tidak valid"], 400);
        }
        
        $data = User::create([
            'Username' => $data['username'],
            'Level' => 'User',
            'Email' => $data['email'],
            'Password' => bcrypt($data['password']),
        ]);

        if(isset($data['firstname']) && isset($data['lastname'])) {
            $data['nama_lengkap'] = $this->gabungNamaLengkap($data['firstname'], $data['lastname']);
        } else {
            $data['nama_lengkap'] = '-';
        }

        $data_detailusers = DetailsUser::create([
            'UserID' => $data['id'],
            'NamaLengkap' => $data['nama_lengkap'],
        ]);

        return response([
            "status" => 201, 
            "message" => "Registrasi Berhasil", 
            'data' => new UserRecource($data)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $User)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $User)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $User)
    {
        //
    }

    private function gabungNamaLengkap($firstname,$lastname) {
        return $firstname . " " . $lastname;
    }
}
