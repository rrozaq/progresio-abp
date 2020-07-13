<?php
namespace App\Http\Controllers\v1\Startup;

use App\Http\Resources\FounderResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Founder;
use Auth;

class FounderController extends Controller
{
    public function index()
    {
        return FounderResource::collection(Founder::where('startup_id', Auth::guard('startup')->user()->id)->get());
    }

    public function store(Request $request)
    {
        if($request->hasFile('gambar')){
            $destination_path = './uploads/founder/';
            $this->validate($request, [
                'gambar' => 'required|mimes:jpg,jpeg,png|max:1048',
            ]);
    
            $gambar = time().'.'.$request->gambar->extension();
            $request->gambar->move($destination_path, $gambar);
        }

        $founder = Founder::create(
            array_merge(
                $request->all(),
                [
                    'nama'          => $request->nama,
                    'posisi'        => $request->posisi,
                    'gambar'        => $gambar,
                    'startup_id'    => Auth::guard('startup')->user()->id
                ]
            )
        );
        $response = [
            'status' => 'success',
            'message' => 'Founder Berhasil dibuat',
            'data' => $founder
        ];
        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        $founder = Founder::find($id);
        if(!$founder) {
            $res['message'] = "ID Not Found!";
            return response($res);
        }
        
        if($request->hasFile('gambar')){
            $destination_path = './uploads/founder/';
            $this->validate($request, [
                'gambar' => 'required|mimes:jpg,jpeg,png|max:1048',
            ]);
    
            $gambar = time().'.'.$request->gambar->extension();
            $request->gambar->move($destination_path, $gambar);
        }else{
            $gambar = $founder->gambar;
        }

        $founder->nama = $request->nama;
        $founder->posisi = $request->posisi;
        $founder->gambar = $gambar;

        if($founder->save()){
            $response = [
                'status' => 'success',
                'message' => 'founder Berhasil diupdate',
                'data' => $founder
            ];
            return response()->json($response);
        }
        else{
            $res['message'] = "Failed!";
            return response($res);
        }
    }

    public function destroy($id)
    {
        $founder = Founder::find($id);
        
        if($founder){
            $founder->delete();
            $response = [
                'status' => 'success',
                'message' => 'Founder Berhasil dihapus',
            ];
            return response()->json($response);
        }else{
            $response = [
                'message' => 'id tenan tidak ada',
            ];
            return response()->json($response, 404);
        }
    }

}
