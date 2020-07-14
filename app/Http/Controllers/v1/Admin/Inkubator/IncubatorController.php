<?php
namespace App\Http\Controllers\v1\Admin\Inkubator;

use App\Http\Controllers\Controller;
use App\Http\Resources\IncubatorResource;
use App\Http\Resources\TractionResource;
use Illuminate\Http\Request;
use App\Models\Incubator;
use App\Models\Startup;
use App\Models\Traction;
use App\Models\Paket;
use Carbon\Carbon;


class IncubatorController extends Controller
{
    public function index()
    {
        return IncubatorResource::collection(Incubator::get());
    }

    public function show($id)
    {
        $incubator = Incubator::find($id);
        return empty($incubator) ? 'Not Found' : IncubatorResource::collection(Incubator::with('incubator_profile')->where('id', $id)->get());
    }

    public function update(Request $request, $id)
    {
        Incubator::where('id', $id)
        ->update([
            'name' => $request->name,
            'aktifasi' => $request->aktifasi,
        ]);

        return IncubatorResource::collection(Incubator::get());
    }

    public function delete($id)
    {
        $incubator = Startup::find($id);
        $incubator->delete();

        return response()->json(['massage' => 'success'], 200);
    }

    public function total($data)
    {
        if($data == 'incubator')
            {
                $total = Incubator::get()->count();
                $response = [
                    'message' => 'Total Incubator',
                    'data' =>  ['total' => $total]
                ];
                return response()->json($response, 200);
            }elseif($data == 'startup'){
                $total = Startup::get()->count();
                $response = [
                    'message' => 'Total Startup',
                    'data' =>  ['total' => $total]
                ];
                return response()->json($response, 200);
            }else{
                $response = ['message' => 'Not Found'];
                return response()->json($response, 200);
            }
    }

    public function enable(Request $request)
    {
        Incubator::where('id', $request->id)
        ->update([
            'aktifasi' => $request->aktifasi,
        ]);

        return IncubatorResource::collection(Incubator::get());
    }

    public function getIncubatorByPaket(int $id)
    {
        $paket = Incubator::where('paket_id', $id)->get();
        if(!$paket) {
            return response()->json(['message'=>'tidak ada'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $paket
        ], 200);
    }

    public function updatePaketIncubator(int $id)
    {
        $incubator = Incubator::find($id);
        if(!$incubator){
            return response()->json([
                'message' => 'id inkubator tidak ada',
            ], 404);
        }else{
            $incubator->update([
                'paket_id' => 4,
                'status_pembayaran' => 1
            ]);

            return response()->json([
                'status' => 'success',
            ], 200);
        }
    }

    public function aktifkanPaket(int $id)
    {
        $incubator = Incubator::find($id);
        if(!$incubator){
            return response()->json([
                'message' => 'id inkubator tidak ada',
            ], 404);
        }else{
            $incubator->update([
                'status_pembayaran' => 1,
                'expired' => Carbon::now()->addMonths(1),
            ]);

            return response()->json([
                'status' => 'success',
            ], 200);
        }
    }

    public function cekExpired()
    {
        $incubator = Incubator::get();
        foreach($incubator as $data) {
            if ($data->expired >= Carbon::now()) {
                return response()->json(['message'=>'belum expired','data' => $incubator], 200);
            } else {
                $data->update([
                    'status_pembayaran' => 0,
                    'expired' => NULL,
                    'paket_id' => 0
                ]);
                $incubatorExpired = Incubator::where('expired', NULL)->get();
                return response()->json(['message'=>'sudah expired','data' => $incubatorExpired], 200);
            }
        }
    }


}