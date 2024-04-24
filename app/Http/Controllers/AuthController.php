<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequests;
use App\Http\Requests\UserRegistrasiRequest;
use App\Http\Resources\UserRecource;
use App\Models\DetailsUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        if (!$user || !Hash::check($request->password, $user->Password)) {
            return response([
                "status" => 400,
                "message" => "Email atau password tidak cocok"
            ], 400);
        }

        $user['token'] = $user->createToken('user login')->plainTextToken;

        return response([
            "status" => 200,
            "message" => "Login Berhasil",
            'data' => new UserRecource($user)
        ], 200);
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
                "message" => "Email tidak valid"
            ], 400);
        }

        $data = User::create([
            'Username' => $data['username'],
            'Level' => 'User',
            'Email' => $data['email'],
            'Password' => bcrypt($data['password']),
        ]);

        if (isset($data['firstname']) && isset($data['lastname'])) {
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
            'data' => new UserRecource($data)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function tampilUser()
    {
        $id_user = Auth::user()->id;
        $data = User::where('id', $id_user)->with('detailsuser')->first();
        $data = [
            'id' => $data->id,
            'username' => $data->Username,
            'email' => $data->Email,
            'Level' => $data->Level,
            'Nama Lengkap' => $data->NamaLengkap == "-" ? "" : $data->NamaLengkap,
            'No Telp' => $data->NoTelepon == null ? "" : $data->NoTelepon,
            'Alamat' => $data->Alamat == null ? "" : $data->Alamat,
        ];
        return response(['Status' => 200, 'Message' => 'Berhasil Menampilkan Profil', 'data' => $data], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['success' => true, 'message' => 'Logout success']);
    }

    public function update(Request $request)
{
    $id = Auth::user()->id;
    $data = $request->validate([
        'Username' => 'required',
        'NamaLengkap' => 'required',
        'Email' => 'required|email',
        'NoTelepon' => 'required',
    ]);
    if(!isset($data['Level'])) {
        $data['Level'] = 'User';
    }
    $userupdate = User::where('id', $id)->update([
        'Username' => $data['Username'],
        'NamaLengkap' => $data['NamaLengkap'],
        'Level' => $data['Level'],
        'NoTelepon' => $data['NoTelepon'],
        'Email' => $data['Email'],
    ]);

    $data = User::where('id', $id)->with('detailsuser')->first();
        $data = [
            'id' => $data->id,
            'username' => $data->Username,
            'email' => $data->Email,
            'Level' => $data->Level,
            'Nama Lengkap' => $data->NamaLengkap == "-" ? "" : $data->NamaLengkap,
            'No Telp' => $data->NoTelepon == null ? "" : $data->NoTelepon,
            'Alamat' => $data->Alamat == null ? "" : $data->Alamat,
        ];
        return response(['Status' => 201, 'Message' => 'Berhasil Menampilkan Profil', 'data' => $data], 201);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $User)
    {
        //
    }

    private function gabungNamaLengkap($firstname, $lastname)
    {
        return $firstname . " " . $lastname;
    }
}
