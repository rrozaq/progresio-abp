<?php
namespace App\Http\Controllers\v1\Inkubator;

use App\Http\Resources\AwardResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Award;
use Auth;

class AwardController extends Controller
{
    public function index()
    {
        return AwardResource::collection(Award::where('incubator_id', Auth::guard('incubator')->user()->id)->get());
    }

    public function store(Request $request)
    {
        if($request->hasFile('gambar')){
            $destination_path = './uploads/award/';
            $this->validate($request, [
                'gambar' => 'required|mimes:jpg,jpeg,png|max:1048',
            ]);
    
            $gambar = time().'.'.$request->gambar->extension();
            $request->gambar->move($destination_path, $gambar);
        }else{
            $gambar = '';
        }

        $award = Award::create(
            array_merge(
                $request->all(),
                [
                    'nama'          => $request->nama,
                    'gambar'        => $gambar,
                    'tahun'        => $request->tahun,
                    'incubator_id'  => Auth::guard('incubator')->user()->id
                ]
            )
        );
        $response = [
            'status' => 'success',
            'message' => 'Incubator Berhasil dibuat',
            'data' => $award
        ];
        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        $award = Award::find($id);
        if(!$award) {
            $res['message'] = "ID Not Found!";
            return response($res);
        }
        
        if($request->hasFile('gambar')){
            $destination_path = './uploads/award/';
            $this->validate($request, [
                'gambar' => 'required|mimes:jpg,jpeg,png|max:1048',
            ]);
    
            $gambar = time().'.'.$request->gambar->extension();
            $request->gambar->move($destination_path, $gambar);
        }else{
            $gambar = $award->gambar;
        }

        $award->nama = $request->nama;
        $award->gambar = $gambar;
        $award->tahun = $request->tahun;

        if($award->save()){
            $response = [
                'status' => 'success',
                'message' => 'Award Berhasil diupdate',
                'data' => $award
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
        $award = Award::find($id);
        
        if($award){
            $award->delete();
            $response = [
                'status' => 'success',
                'message' => 'Award Berhasil dihapus',
            ];
            return response()->json($response);
        }else{
            $response = [
                'message' => 'id award tidak ada',
            ];
            return response()->json($response, 404);
        }
    }

}
