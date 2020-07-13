<?php
namespace App\Http\Controllers\v1\Startup;

use App\Http\Controllers\Controller;
use App\Http\Resources\StartupLoginResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Startup;
use App\Models\Startup_profile;
use App\Models\Incubator;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (! $token = Auth::guard('startup')->attempt($request->only(['email', 'password']))) {
            return response()->json(['mesaage' => 'Login Gagal'], 401);
        }
        
        return new StartupLoginResource((object)self::responWithToken($token));
    }

    public function register(Request $request)
    {
        $find = Incubator::where('incubator_code', $request->kode_inkubator)->first();
        $request->offsetUnset('kode_inkubator');
        $startup = Startup::create(
            array_merge(
                $request->all(),
                [
                    'email' => $request->email,
                    'visible_password' => $request->password,
                    'password'  => Hash::make($request->password),
                    'incubator_id'  => $find->id
                ]
            )
        );
        Startup_profile::create([
                'startup_id' => $startup->id,
            ]
        );

        $response = [
            'status' => 'success',
            'message' => 'Startup Berhasil dibuat',
            'data' => $startup
        ];
        return response()->json($response);
    }

    public static function responWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('startup')->factory()->getTTL() * 60 * 3,
            'startup' => Auth::guard('startup')->user()
        ];
    }
}