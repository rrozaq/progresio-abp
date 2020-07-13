<?php
namespace App\Http\Controllers\v1\Inkubator;

use App\Http\Resources\MentorResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mentor;
use Auth;

class MentorController extends Controller
{
    public function index()
    {
        return MentorResource::collection(Mentor::where('incubator_id', Auth::guard('incubator')->user()->id)->get());
    }

    public function store(Request $request)
    {
        if($request->hasFile('gambar')){
            $destination_path = './uploads/mentor/';
            $this->validate($request, [
                'gambar' => 'required|mimes:jpg,jpeg,png|max:1048',
            ]);
    
            $gambar = time().'.'.$request->gambar->extension();
            $request->gambar->move($destination_path, $gambar);
        }else{
            $gambar = '';
        }

        $mentor = Mentor::create(
            array_merge(
                $request->all(),
                [
                    'nama'          => $request->nama,
                    'posisi'        => $request->posisi,
                    'gambar'        => $gambar,
                    'incubator_id'  => Auth::guard('incubator')->user()->id
                ]
            )
        );
        $response = [
            'status' => 'success',
            'message' => 'Incubator Berhasil dibuat',
            'data' => $mentor
        ];
        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        $mentor = Mentor::find($id);
        if(!$mentor) {
            $res['message'] = "ID Not Found!";
            return response($res);
        }
        
        if($request->hasFile('gambar')){
            $destination_path = './uploads/mentor/';
            $this->validate($request, [
                'gambar' => 'required|mimes:jpg,jpeg,png|max:1048',
            ]);
    
            $gambar = time().'.'.$request->gambar->extension();
            $request->gambar->move($destination_path, $gambar);
        }else{
            $gambar = $mentor->gambar;
        }

        $mentor->nama = $request->nama;
        $mentor->gambar = $gambar;
        $mentor->posisi = $request->posisi;

        if($mentor->save()){
            $response = [
                'status' => 'success',
                'message' => 'mentor Berhasil diupdate',
                'data' => $mentor
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
        $mentor = Mentor::find($id);
        
        if($mentor){
            $mentor->delete();
            $response = [
                'status' => 'success',
                'message' => 'Mentor Berhasil dihapus',
            ];
            return response()->json($response);
        }else{
            $response = [
                'message' => 'id mentor tenan tidak ada',
            ];
            return response()->json($response, 404);
        }
    }

}
