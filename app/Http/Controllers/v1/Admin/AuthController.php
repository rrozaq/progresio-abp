<?php
namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminLoginResource;
use Illuminate\Http\Request;
use App\Models\Admin;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (! $token = Auth::guard('admin')->attempt($request->only(['username', 'password']))) {
            return response()->json(['mesaage' => 'Login Gagal'], 401);
        }
        
        return new AdminLoginResource((object)self::responWithToken($token));
    }

    public static function responWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('admin')->factory()->getTTL() * 60 * 3,
            'admin' => Auth::guard('admin')->user()
        ];
    }
}