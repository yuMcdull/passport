<?php

namespace App\Http\Controllers\Api;

use App\Models\Admin;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    use AuthenticatesUsers;

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:admins',
            'password' => 'required|string',
            'password_confirmation' => 'required|same:password',
        ]);

        $admin = new Admin([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $admin->save();

        return response()->json(['message' => 'Successfuly create admin.']);
    }

    public function login(Request $request)
    {
        $client = new Client();
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required',
            'remember_me' => 'boolean'
        ]);

        $admin = Admin::where('email', $request->email)->first();
        if (!$admin) {
            return response()->json(['message' => 'Not find admin.' ,401]);
        }
        if ($request->email == $admin->email && Hash::check($request->password, $admin->password)) {
            $data =  [
                'client_id' => '2',
                'client_secret' => 'SoYwJgJxHFSRbug8lf6SwV1eF0EjdoQTuOfxaFSf',
                'grant_type' => 'password',
                'scope' => '*',
                'username' => $request->email,
                'password' => $request->password,
                'provider' => 'admins'
            ];
            $getToken = $client->post('http://mypass.test/oauth/token', [
                'form_params' => $data
            ]);
            $token = json_decode((string)$getToken->getBody(), true);
            return response()->json([
                'data' => $token
            ]);
        } else {
            return response()->json(['message' => 'admin password wrong.' ,401]);
        }
    }

    public function admin(Request $request)
    {
        return response()->json([
            'data' => $request->user('admins')
        ]);
    }

    public function logout(Request $request)
    {
        $request->user('admins')->token()->revoke();
        return response()->json(['message' => 'Successfully loggen out.']);
    }
}
