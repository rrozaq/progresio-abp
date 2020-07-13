<?php
namespace App\Http\Controllers\v1\Inkubator;

use App\Http\Controllers\Controller;
use App\Http\Resources\IncubatorLoginResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Incubator;
use App\Models\Incubator_profile;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (! $token = Auth::guard('incubator')->attempt($request->only(['email', 'password']))) {
            return response()->json(['mesaage' => 'Login Gagal'], 401);
        }
        
        return new IncubatorLoginResource((object)self::responWithToken($token));
    }

    public function register(Request $request)
    {
        $incubator = Incubator::create(
            array_merge(
                $request->all(),
                [
                    'incubator_code' => floor(time()-999999999),
                    'email' => $request->email,
                    'visible_password' => $request->password,
                    'password'  => Hash::make($request->password)
                ]
            )
        );

        Incubator_profile::create([
                'incubator_id' => $incubator->id,
            ]
        );

        $response = [
            'status' => 'success',
            'message' => 'Incubator Berhasil dibuat',
            'data' => $incubator
        ];
        return response()->json($response);
    }

    public static function responWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('incubator')->factory()->getTTL() * 60 * 3,
            'incubator' => Auth::guard('incubator')->user()
        ];
    }
}