<?php
namespace App\Http\Controllers\v1\Inkubator;

use App\Http\Resources\TractionResource;
use App\Http\Resources\TractionJawabanResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Traction;
use App\Models\SoalTraction;
use App\Models\Startup;
use App\Models\TractionJawaban;
use Auth;
use DB;

class TractionController extends Controller
{
    public function index()
    {
        $traction = Traction::where('incubator_id', Auth::guard('incubator')->user()->id)->get();

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

    public function show($id)
    {
        $traction = Traction::where('incubator_id', Auth::guard('incubator')->user()->id)->where('id', $id)->get();
        
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

    public function store(Request $request)
    {
        $traction = Traction::create([
                    'judul'         => $request->judul_traction,
                    'description'   => $request->description,
                    'incubator_id'  => Auth::guard('incubator')->user()->id
                ]
        );
        
        foreach($request->all()['pertanyaan'] as $data){
            SoalTraction::create([
                    'judul_id'   => $traction->id,
                    'pertanyaan'  => $data['pertanyaan'],
                ]
            );
        }
        $response = [
            'status' => 'success',
            'message' => 'Incubator Berhasil dibuat',
        ];
        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        Traction::where('id', $id)
        ->update([
            'judul' => $request->judul_traction,
            'description' => $request->description,
        ]);

        foreach($request->all()['pertanyaan'] as $data){
            $ids = isset($data['id']) ? $data['id'] : null;

            SoalTraction::updateOrCreate(
                [
                    'id' => $ids
                ],
                [
                    'judul_id'  => $id,
                    'pertanyaan' => $data['pertanyaan'],
                ]
            );
        }

        return $this->index();
    }

    public function destroy($id)
    {
        $traction = Traction::find($id);
        
        if($traction){
            $traction->delete();
            SoalTraction::where('judul_id', $id)->delete();

            $response = [
                'status' => 'success',
                'message' => 'Traction Berhasil dihapus',
            ];
            return response()->json($response);
        }else{
            $response = [
                'message' => 'id tractions tenan tidak ada',
            ];
            return response()->json($response, 404);
        }
    }

    // View Traction
    public function view($id)
    {
        $traction = SoalTraction::where('judul_id', $id)->get();
            foreach($traction as $value){
                $jawaban = DB::table('traction_jawaban')
                    ->select(DB::raw("startups.id as id_startup, startups.name, traction_jawaban.created_at, profile_startups.manager_name"))
                    ->join('startups', 'startups.id', '=', 'traction_jawaban.startup_id')
                    ->join('profile_startups', 'startups.id', '=', 'profile_startups.startup_id')
                    ->where('traction_jawaban.traction_soal_id', $value->id)
                    ->get();
                    return response()->json(['data' => $jawaban]);
                }
    }

    public function viewRespon($judul, $startup)
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
    
    public function showDashboard(Request $request)
    {
        $startup = Startup::whereIn('id', $request->startup)
                ->where('incubator_id', Auth::guard('incubator')->user()->id)
                ->get();
        
        if($startup) {

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
        }else{
            $response = [
                'message' => 'id startup tidak ada / id startup incubator lain',
            ];
            return response()->json($response, 404);
        }
            return response()->json(['data' => $data]);
        
    }

}
