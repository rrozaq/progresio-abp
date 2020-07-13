<?php
namespace App\Http\Controllers\v1\Startup;

use App\Http\Resources\ProductResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Auth;

class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(Product::where('startup_id', Auth::guard('startup')->user()->id)->get());
    }

    public function store(Request $request)
    {
        if($request->hasFile('gambar')){
            $destination_path = './uploads/product/';
            $this->validate($request, [
                'gambar' => 'required|mimes:jpg,jpeg,png|max:1048',
            ]);
    
            $gambar = time().'.'.$request->gambar->extension();
            $request->gambar->move($destination_path, $gambar);
        }

        $product = Product::create(
            array_merge(
                $request->all(),
                [
                    'nama'          => $request->nama,
                    'deskripsi'        => $request->deskripsi,
                    'gambar'        => $gambar,
                    'startup_id'    => Auth::guard('startup')->user()->id
                ]
            )
        );
        $response = [
            'status' => 'success',
            'message' => 'Product Berhasil dibuat',
            'data' => $product
        ];
        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if(!$product) {
            $res['message'] = "ID Not Found!";
            return response($res);
        }
        
        if($request->hasFile('gambar')){
            $destination_path = './uploads/product/';
            $this->validate($request, [
                'gambar' => 'required|mimes:jpg,jpeg,png|max:1048',
            ]);
    
            $gambar = time().'.'.$request->gambar->extension();
            $request->gambar->move($destination_path, $gambar);
        }else{
            $gambar = $product->gambar;
        }

        $product->nama = $request->nama;
        $product->deskripsi = $request->deskripsi;
        $product->gambar = $gambar;

        if($product->save()){
            $response = [
                'status' => 'success',
                'message' => 'founder Berhasil diupdate',
                'data' => $product
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
        $product = Product::find($id);
        
        if($product){
            $product->delete();
            $response = [
                'status' => 'success',
                'message' => 'Product Berhasil dihapus',
            ];
            return response()->json($response);
        }else{
            $response = [
                'message' => 'id product tidak ada',
            ];
            return response()->json($response, 404);
        }
    }

}
