<?php
namespace App\Http\Controllers\v1\Admin\Inkubator;

use App\Http\Controllers\Controller;
use App\Http\Resources\IncubatorResource;
use App\Http\Resources\TractionResource;
use Illuminate\Http\Request;
use App\Models\Incubator;
use App\Models\Startup;
use App\Models\Traction;

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



}