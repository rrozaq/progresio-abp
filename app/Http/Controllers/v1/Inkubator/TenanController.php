<?php
namespace App\Http\Controllers\v1\Inkubator;

use App\Http\Resources\TenanResource;
use App\Http\Resources\KategoriTenanResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenan;
use App\Models\KategoriTenan;
use Auth;

class TenanController extends Controller
{
    public function index()
    {
        return TenanResource::collection(Tenan::where('incubator_id', Auth::guard('incubator')->user()->id)->get());
    }

    public function store(Request $request)
    {
        if($request->hasFile('logo')){
            $destination_path = './uploads/tenan/';
            $this->validate($request, [
                'logo' => 'required|mimes:jpg,jpeg,png|max:1048',
            ]);
    
            $logo = time().'.'.$request->logo->extension();
            $request->logo->move($destination_path, $logo);
        }

        $tenan = Tenan::create(
            array_merge(
                $request->all(),
                [
                    'nama'          => $request->nama,
                    'kategori_id'   => $request->kategori_id,
                    'logo'          => $logo,
                    'incubator_id'  => Auth::guard('incubator')->user()->id
                ]
            )
        );
        $response = [
            'status' => 'success',
            'message' => 'Tenan Berhasil dibuat',
            'data' => $tenan
        ];
        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        $tenan = Tenan::find($id);
        if(!$tenan) {
            $res['message'] = "ID Not Found!";
            return response($res);
        }
        
        if($request->hasFile('logo')){
            $destination_path = './uploads/tenan/';
            $this->validate($request, [
                'logo' => 'required|mimes:jpg,jpeg,png|max:1048',
            ]);
    
            $logo = time().'.'.$request->logo->extension();
            $request->logo->move($destination_path, $logo);
        }else{
            $logo = $tenan->logo;
        }

        $tenan->nama = $request->nama;
        $tenan->kategori_id = $request->kategori_id;
        $tenan->logo = $logo;

        if($tenan->save()){
            $response = [
                'status' => 'success',
                'message' => 'tenan Berhasil diupdate',
                'data' => $tenan
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
        $tenan = Tenan::find($id);
        
        if($tenan){
            $tenan->delete();
            $response = [
                'status' => 'success',
                'message' => 'Tenan Berhasil dihapus',
            ];
            return response()->json($response);
        }else{
            $response = [
                'message' => 'id tenan tidak ada',
            ];
            return response()->json($response, 404);
        }
    }

    // Kategori Tenan

    public function kategoriTenan()
    {
        return KategoriTenanResource::collection(KategoriTenan::get());
    }

    public function storeKategoriTenan(Request $request)
    {
        $tenan = KategoriTenan::create(
            array_merge(
                $request->all(),
                [
                    'nama' => $request->nama,
                ]
            )
        );
        $response = [
            'status' => 'success',
            'message' => 'Kategori Tenan Berhasil dibuat',
            'data' => $tenan
        ];
        return response()->json($response); 
    }

    public function updateKategoriTenan(Request $request, $id)
    {
        $kategoriTenan = KategoriTenan::find($id);
        if(!$kategoriTenan) {
            $res['message'] = "ID Not Found!";
            return response($res);
        }

        $kategoriTenan->nama = $request->nama;

        if($kategoriTenan->save()){
            $response = [
                'status' => 'success',
                'message' => 'Kategori tenan Berhasil diupdate',
                'data' => $kategoriTenan
            ];
            return response()->json($response);
        }
        else{
            $res['message'] = "Failed!";
            return response($res);
        }
    }

    public function destroyKategori($id)
    {
        $tenan = KategoriTenan::find($id);
        
        if($tenan){
            $tenan->delete();
            $response = [
                'status' => 'success',
                'message' => 'KategoriTenan Berhasil dihapus',
            ];
            return response()->json($response);
        }else{
            $response = [
                'message' => 'id kategori tenan tidak ada',
            ];
            return response()->json($response, 404);
        }
    }

}
