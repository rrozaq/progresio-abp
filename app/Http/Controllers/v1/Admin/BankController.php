<?php
namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;

class BankController extends Controller
{
    public function index()
    {
        $bank = Bank::get();
        $response = [
            'status' => 'success',
            'data' => $bank,
        ];

        return response()->json($response, 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required',
            'norek' => 'required',
            'jenis' => 'required',
        ]);
  
        $bank = Bank::create($request->all());
        $response = [
            'status' => 'success',
            'data' => $bank,
        ];
        return response()->json($response, 200);
    }

    public function update(Request $request, int $id)
    {
        $this->validate($request, [
            'nama' => 'required',
            'norek' => 'required',
            'jenis' => 'required',
        ]);
        Bank::where('id', $id)->update($request->all());

        $response = [
            'status' => 'success',
        ];
        return response()->json($response, 200);
    }


    public function destroy(int $id)
    {
        $bank = Bank::find($id);
        if($bank->delete()){
            $response = [
                'status' => 'success',
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'message' => 'ID not found',
            ];
            return response()->json($response, 200);
        }
    }
}