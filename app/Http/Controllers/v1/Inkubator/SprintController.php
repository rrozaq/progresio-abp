<?php
namespace App\Http\Controllers\v1\Inkubator;

use App\Http\Resources\BoardResource;
use App\Http\Resources\ListBoardResource;
use App\Http\Resources\CardResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Board;
use App\Models\Card;
use App\Models\ListBoard;
use Auth;
use DB;

class SprintController extends Controller
{

    public function board($id)
    {
        return BoardResource::collection(Board::where('startup_id', $id)->get());
    }

    public function store(Request $request)
    {
        $board = Board::create(
            array_merge(
                $request->all(),
                [
                    'nama'          => $request->nama,
                    'deadline'      => $request->deadline,
                    'startup_id'    => $request->startup_id
                ]
            )
        );
        $response = [
            'status' => 'success',
            'message' => 'Sprint Berhasil dibuat',
            'data' => $board
        ];
        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        $board = Board::find($id);
        if(!$board) {
            $res['message'] = "ID Not Found!";
            return response($res);
        }

        $board->nama = $request->nama;
        $board->deadline = $request->deadline;

        if($board->save()){
            $response = [
                'status' => 'success',
                'message' => 'Sprint Berhasil diupdate',
                'data' => $board
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
        $board = Board::find($id);
        
        if($board){
            $board->delete();
            $response = [
                'status' => 'success',
                'message' => 'Board Berhasil dihapus',
            ];
            return response()->json($response);
        }else{
            $response = [
                'message' => 'id Board tidak ada',
            ];
            return response()->json($response, 404);
        }
    }


    // List in Board
    public function list($id)
    {
        return ListBoardResource::collection(ListBoard::where('board_id', $id)->get());
    }

    public function storeList(Request $request)
    {
        $list = ListBoard::create(
            array_merge(
                $request->all(),
                [
                    'nama'          => $request->nama,
                    'board_id'    => $request->board_id
                ]
            )
        );
        $response = [
            'status' => 'success',
            'message' => 'List Board Berhasil dibuat',
            'data' => $list
        ];
        return response()->json($response);
    }

    public function updateList(Request $request, $id)
    {
        $list = ListBoard::find($id);
        if(!$list) {
            $res['message'] = "ID Not Found!";
            return response($res);
        }

            $list->nama = $request->nama;
            $list->board_id = $request->board_id;

        if($list->save()){
            $response = [
                'status' => 'success',
                'message' => 'List Board Berhasil diupdate',
                'data' => $list
            ];
            return response()->json($response);
        }
        else{
            $res['message'] = "Failed!";
            return response($res);
        }

    }

    public function destroyList($id)
    {
        $list = ListBoard::find($id);
        
        if($list){
            $list->delete();
            $response = [
                'status' => 'success',
                'message' => 'List Board Berhasil dihapus',
            ];
            return response()->json($response);
        }else{
            $response = [
                'message' => 'id Board tidak ada',
            ];
            return response()->json($response, 404);
        }
    }

    // kanggo
    public function moveList($idListPindah, $idListkePindah)
    {
        $getIdDipindah = ListBoard::where('id', '=', $idListPindah)
                    ->first()
                    ->urut;
                    // 6, 1

        $getIdKepindah = ListBoard::where('id', '=', $idListkePindah)
                    ->first()
                    ->urut;
                    // 7, 2

        DB::table('lists')
        ->where('id', '=', $idListPindah)
        ->update(['urut' => $getIdKepindah]);

        DB::table('lists')
        ->where('id', '=', $idListkePindah)
        ->update(['urut' => $getIdDipindah]);

        $response = [
            'status' => 'success',
            'message' => 'Card Berhasil dipindah',
        ];
        return response()->json($response);

    }


    // Card in List
    public function card($id)
    {
        return CardResource::collection(Card::where('list_id', $id)->get());
    }

    public function getCardbyId($id)
    {
        return CardResource::collection(Card::where('id', $id)->get());
    }

    public function storeCard(Request $request)
    {
        $card = Card::create(
            array_merge(
                $request->all(),
                [
                    'title'          => $request->title,
                    'list_id'        => $request->list_id,
                    'urut'           => $request->urut,
                    'startup_id'     => $request->startup_id
                ]
            )
        );
        $response = [
            'status' => 'success',
            'message' => 'Card Berhasil dibuat',
            'data' => $card
        ];
        return response()->json($response);
    }

    public function updateCardNama(Request $request, $id)
    {
        $card = Card::find($id);
        if(!$card) {
            $res['message'] = "ID Not Found!";
            return response($res);
        }

        $card->title = $request->title;

        if($card->save()){
            $response = [
                'status' => 'success',
                'message' => 'List Board Berhasil diupdate',
                'data' => $card
            ];
            return response()->json($response);
        }
        else{
            $res['message'] = "Failed!";
            return response($res);
        }

    }

    public function updateCardDescription(Request $request, $id)
    {
        $card = Card::find($id);
        if(!$card) {
            $res['message'] = "ID Not Found!";
            return response($res);
        }

        $card->description = $request->description;

        if($card->save()){
            $response = [
                'status' => 'success',
                'message' => 'List Board Berhasil diupdate',
                'data' => $card
            ];
            return response()->json($response);
        }
        else{
            $res['message'] = "Failed!";
            return response($res);
        }

    }

    public function updateCardBerkas(Request $request, $id)
    {
        $card = Card::find($id);
        if(!$card) {
            $res['message'] = "ID Not Found!";
            return response($res);
        }

        if($request->hasFile('berkas')){
            $destination_path = './uploads/berkas/';
            $this->validate($request, [
                'berkas' => 'required|mimes:jpg,jpeg,png,pdf,txt,zip,rar,pptx,ppt,doc,docx|max:2048',
            ]);
    
            $berkas = time().'.'.$request->berkas->extension();
            $request->berkas->move($destination_path, $berkas);
        }else{
            return 'Berkas belum dipilih';
        }

        Card::updateOrCreate(
            [
                'id' => $card->id,
            ],
            [
                'berkas' => $berkas
            ]
        );

        $response = [
            'status' => 'success',
            'message' => 'Berkas Card Berhasil diupdate',
            'data' => $card
        ];
        return response()->json($response);
    
    }

    // get idcard $id -> ubah id list $idTo   
    public function moveCardtoSprint($idcard, $idlist, $idListAsal)
    {
        $card = Card::where('id', $idcard)->first();
        if(!$card) {
            $res['message'] = "ID Not Found!";
            return response($res);
        }
        
        // update list tujuan +1
        Card::where('list_id', $idlist)
        ->where('urut', '>=', 1)
        ->update([
            'urut' => DB::raw('urut+1')
        ]);
        
        DB::statement("UPDATE cards SET urut = urut-1 WHERE list_id = '$idListAsal' AND urut > '$card->urut'");

        $card->list_id = $idlist;

        if($card->save()){
            $response = [
                'status' => 'success',
                'message' => 'List Board Berhasil dipindah',
                'data' => $card
            ];
            return response()->json($response);
        }
        else{
            $res['message'] = "Failed!";
            return response($res);
        }
    }

    public function copyCardtoSprint($idcard, $idlist, $idStartup)
    {
        $card = Card::where('id', $idcard)->first();
        if(!$card) {
            $res['message'] = "ID Not Found!";
            return response($res);
        }
        
        Card::where('list_id', $idlist)
        ->where('urut', '>=', 1)
        ->update([
            'urut' => DB::raw('urut+1')
        ]);
        
        $create = Card::create(
                [
                    'title'          => $card->title,
                    'list_id'        => $idlist,
                    'description'    => $card->description,
                    'berkas'         => $card->berkas,
                    'urut'           => 1,
                    'startup_id'     => $idStartup
                ]
        );

        if($create->save()){
            $response = [
                'status' => 'success',
                'message' => 'List Board Berhasil disalin',
                'data' => $create
            ];
            return response()->json($response);
        }
        else{
            $res['message'] = "Failed!";
            return response($res);
        }
    }

    // kanggo
    public function moveCard($idCardPindah, $idCardkePindah)
    {
        $getIdDipindah = Card::where('id', '=', $idCardPindah)
                    ->first()
                    ->urut;

        $getIdKepindah = Card::where('id', '=', $idCardkePindah)
                    ->first()
                    ->urut;

        DB::table('cards')
        ->where('id', '=', $idCardPindah)
        ->update(['urut' => $getIdKepindah]);

        DB::table('cards')
        ->where('id', '=', $idCardkePindah)
        ->update(['urut' => $getIdDipindah]);

        $response = [
            'status' => 'success',
            'message' => 'Card Berhasil dipindah',
        ];
        return response()->json($response);
    }
    
    // kanggo
    public function moveCardtoList(Request $request)
    {
        // update urut card tujuan +1
        Card::where('list_id', $request->idList)
        ->where('urut', '>=', $request->urut)
        ->update([
            'urut' => DB::raw('urut+1')
        ]);
     
        // Update urut -1 id list asal   
        // Card::where('list_id', $request->ListAsal)
        // ->where('urut', '>', $request->urutCardAsal)
        // ->decrement('urut', 1);
        
        DB::statement("UPDATE cards SET urut = urut-1 WHERE list_id = '$request->listAsal' AND urut > '$request->urutCardAsal'");
        
        // move card to list and change no urut
        Card::where('id', $request->idCard)
        ->update([
            'urut' => $request->urut,
            'list_id'   => $request->idList
        ]);
        
        $response = [
            'status' => 'success',
            'message' => 'Card Berhasil dipindah di list',
        ];
        return response()->json($response);
    }
    
    public function destroyCard($id, $idListAsal)
    {
        $card = Card::where('id', $id)->first();
        
        if($card){
            $card->delete();
            
            DB::statement("UPDATE cards SET urut = urut-1 WHERE list_id = '$idListAsal' AND urut > '$card->urut'");
            
            $response = [
                'status' => 'success',
                'message' => 'Card Berhasil dihapus',
            ];
            return response()->json($response);
        }else{
            $response = [
                'message' => 'id Board tidak ada',
            ];
            return response()->json($response, 404);
        }
    }

}