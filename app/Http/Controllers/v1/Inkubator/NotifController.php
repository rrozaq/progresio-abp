<?php
namespace App\Http\Controllers\v1\Inkubator;

use App\Http\Controllers\Controller;
use App\Http\Resources\StartupResource;
use Illuminate\Http\Request;
use App\Models\Startup;
use App\Models\Paket;
use Auth;


class NotifController extends Controller
{
    public function getJoin()
    {
        return StartupResource::collection(Startup::with('startup_profile')
            ->where('incubator_id', Auth::guard('incubator')->user()->id)
            ->where('accept', 0)
            ->get()
        );
    }

    public function acceptJoin(Request $request)
    {
        $config = Paket::where('id', Auth::guard('incubator')->user()->paket_id)->first();
        $totalStartup = Startup::where('incubator_id', Auth::guard('incubator')->user()->id)->where('accept', 1);

        $array = array(
            1 => 'Paket Anda Free. batas maksimal ' .$config->value. ' Startup, Silahkan upgrade paket untuk menambah jumlah maksimal startup.',
            2 => 'Paket Anda Basic. batas maksimal ' .$config->value. ' Startup, Silahkan upgrade paket untuk menambah jumlah maksimal startup.',
            3 => 'Paket Anda Platinum. batas maksimal ' .$config->value. ' Startup, Silahkan upgrade paket ke unlimited.',
        );
        
        if($totalStartup->count() >= $config->value){
            $response = [
                'status' => 'warning',
                'message' => $array[Auth::guard('incubator')->user()->paket_id]
            ];
        }else{
            Startup::where('id', $request->id_startup)
            ->update([
                'accept'  => 1,
            ]);
            $response = [
                'status' => 'success',
                'message' => 'Startup Berhasil ditambahkan',
            ];
        }

        return response()->json($response);

    }

}