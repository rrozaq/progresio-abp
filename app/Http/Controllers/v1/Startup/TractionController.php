<?php
namespace App\Http\Controllers\v1\Startup;

use App\Http\Resources\TractionResource;
use App\Http\Resources\TractionJawabanResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Traction;
use App\Models\SoalTraction;
use App\Models\TractionJawaban;
use App\Models\Startup;
use Auth;
use DB;

class TractionController extends Controller
{
    public function index()
    {
        $traction = Traction::where('incubator_id', Auth::guard('startup')->user()->incubator_id)->get();

        $data = [];
        foreach($traction as $row){
            $temp = [
                'id' => $row->id,
                'judul' => $row->judul,
                'description' => $row->description,
                'pertanyaan' => [],
                'jumlah_jawaban' => [],
                'created_at'    => $row->created_at->diffForHumans()
            ];
            
            $soalTraction = SoalTraction::select('id', 'pertanyaan')->where('judul_id', $row->id)->get();
            foreach($soalTraction as $value){
                
                $jumlah_jawaban = DB::table('traction_jawaban')
                ->select(DB::raw("COUNT(DISTINCT startup_id)"))
                ->where('traction_soal_id', $value->id)
                ->get();

                $jumlah_jawaban = TractionJawaban::where('traction_soal_id', $value->id)->distinct('startup_id')->count('startup_id');
                $temp['jumlah_jawaban'] = $jumlah_jawaban;
            }

            $temp['pertanyaan'] = $soalTraction;
            array_push($data, $temp);
        }

        return response()->json(['data' => $data]);
    }
    
    public function showDashboard(Request $request)
    {
        $startup = Startup::whereIn('id', $request->startup)->get();

        $data = [];
        foreach($startup as $raw){
            $temp = [
                'id'    => $raw->id,
                'name'  => $raw->name,
                'traction' => []
            ];

            $jawaban = TractionJawaban::select('soal_tractions.pertanyaan', 'traction_jawaban.jawaban')
                ->join('soal_tractions', 'soal_tractions.id', '=', 'traction_jawaban.traction_soal_id')
                ->where('soal_tractions.judul_id', $request->traction)
                ->where('traction_jawaban.startup_id', $raw->id)
                ->get();

                $temp['traction'] = $jawaban;
                array_push($data, $temp);
        }
        
        return response()->json(['data' => $data]);
    }
    
    public function show($id)
    {
        $traction = Traction::where('incubator_id', Auth::guard('startup')->user()->incubator_id)->where('id', $id)->get();

        $data = [];
        foreach($traction as $value){

            $temp = [
                'id' => $value->id,
                'judul' => $value->judul,
                'detail' => []
            ];

            $jawaban = TractionJawaban::select('soal_tractions.pertanyaan', 'traction_jawaban.jawaban')
            ->join('soal_tractions', 'soal_tractions.id', '=', 'traction_jawaban.traction_soal_id')
            ->where('soal_tractions.judul_id', $value->id)
            ->get();

            $temp['detail'] = $jawaban;
            array_push($data, $temp);
        }

        return response()->json(['data' => $data]);
    }

    public function viewTraction($id)
    {
        $traction = Traction::where('id', $id)->first();

        $data = [];
        $temp = [
            'id' => $traction->id,
            'judul' => $traction->judul,
            'description' => $traction->description,
            'pertanyaan' => [],
            'jumlah_jawaban' => [],
            'created_at'    => $traction->created_at->diffForHumans()
        ];
            
        $soalTraction = SoalTraction::select('id', 'pertanyaan')->where('judul_id', $traction->id)->get();
        foreach($soalTraction as $value){
            
            $jumlah_jawaban = DB::table('traction_jawaban')
            ->select(DB::raw("COUNT(DISTINCT startup_id)"))
            ->where('traction_soal_id', $value->id)
            ->get();

            $jumlah_jawaban = TractionJawaban::where('traction_soal_id', $value->id)->distinct('startup_id')->count('startup_id');
            $temp['jumlah_jawaban'] = $jumlah_jawaban;
        }

        $temp['pertanyaan'] = $soalTraction;
        array_push($data, $temp);
    

        return response()->json(['data' => $data]);
    }

    public function submitTraction(Request $request)
    {
        foreach($request->all() as $jawaban){
            
            TractionJawaban::updateOrCreate(
                [
                    'traction_soal_id' => $jawaban['traction_soal_id'],
                    'startup_id'       => Auth::guard('startup')->user()->id,
                ],
                [                
                    'jawaban'          => $jawaban['jawaban'],
                ]
            );
        }
    }

    public function viewRespon($judul)
    {
        $traction = DB::table('tractions')
                ->where('id', $judul)
                ->get();
        
        $data = [];
        foreach($traction as $value){

            $temp = [
                'id' => $value->id,
                'judul' => $value->judul,
                'detail' => []
            ];

            $jawaban = TractionJawaban::select('soal_tractions.pertanyaan', 'traction_jawaban.jawaban')
            ->join('soal_tractions', 'soal_tractions.id', '=', 'traction_jawaban.traction_soal_id')
            ->where('soal_tractions.judul_id', $judul)
            ->where('traction_jawaban.startup_id', Auth::guard('startup')->user()->id)
            ->get();

            $temp['detail'] = $jawaban;
            array_push($data, $temp);
        }

        return response()->json(['data' => $data]);
    }

    public function getStartupByID($judul, $startup)
    {
        $traction = DB::table('tractions')
                ->where('id', $judul)
                ->get();
        
        $data = [];
        foreach($traction as $value){

            $temp = [
                'id' => $value->id,
                'judul' => $value->judul,
                'detail' => []
            ];

            $jawaban = TractionJawaban::select('soal_tractions.pertanyaan', 'traction_jawaban.jawaban')
            ->join('soal_tractions', 'soal_tractions.id', '=', 'traction_jawaban.traction_soal_id')
            ->where('soal_tractions.judul_id', $judul)
            ->where('traction_jawaban.startup_id', $startup)
            ->get();

            $temp['detail'] = $jawaban;
            array_push($data, $temp);
        }

        return response()->json(['data' => $data]);
    }
    
}
