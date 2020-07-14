<?php
namespace App\Http\Controllers\v1\Inkubator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Startup;
use App\Models\Incubator;
use App\Models\Paket;
use Auth;
use Illuminate\Support\Facades\Mail;

class PembayaranController extends Controller
{
    public function bayar(Request $request)
    {
        $paket = Paket::find($request->id_paket);
        if(!$paket) {
            $res['message'] = "ID Not Found!";
            return response($res);
        }
        $incubator = Incubator::where('id', Auth::guard('incubator')->user()->id)
            ->update([
                'paket_id'  => $paket->id,
            ]);

        if($incubator){
            $response = [
                'status' => 'success',
                'message' => 'Pembelian Paket Berhasil'
            ];
        }else{
            $response = [
                'status' => 'gagal',
            ];
        }
        return response()->json($response);
    }

    public function konfirmasi(Request $request)
    {
        $destination_path = './uploads/konfirmasi/';
        $validator = \Validator::make($request->all(), [
            'gambar' => 'required|mimes:jpg,jpeg,png|max:1048',
        ]);
        if($validator->fails()){
            return response()->json([
                    'status' => 'gagal',
                    'message' => $validator->errors()
                ]);
        }else{
            $gambar = Auth::guard('incubator')->user()->name."-".time().'.'.$request->gambar->extension();
            $request->gambar->move($destination_path, $gambar);
        }

        $data = array(
            'name' => 'TES',
            'email' => 'tesabp@mailnesia.com',
            'gambar' => 'http://progresio.id/api/public/uploads/logo/1591673972.jpeg'
        );

        Mail::send('emails.konfirmasi', $data, function($message) use ($data){
            $message
                ->from('roozsec@gmail.com', 'Progresio')
                ->to($data['email'], $data['name'])
                ->subject('Konfirmasi Pembayaran')
                ->attach($data['gambar']);
        });

        return 'success';
    }

    public function riwayat_menunggu()
    {
        $incubator = Incubator::where('id', Auth::guard('incubator')->user()->id)->where('status_pembayaran', 0)->with('paket')->get();
        
        $response = [
            'status' => 'success',
            'data' => $incubator
        ];

        return response()->json($response);
    }

}
