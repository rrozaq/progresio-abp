<?php
namespace App\Http\Controllers\v1\Admin\Startup;

use App\Http\Controllers\Controller;
use App\Http\Resources\StartupResource;
use Illuminate\Http\Request;
use App\Models\Startup;

class StartupController extends Controller
{
    public function index()
    {
        return StartupResource::collection(Startup::get());
    }

    public function update(Request $request, $id)
    {
        Startup::where('id', $id)
        ->update([
            'name' => $request->name,
            'school_id' => auth('school')->id()
        ]);

        return GroupResource::collection(Group::all());
    }

    public function delete($id)
    {
        $startup = Startup::find($id);
        $startup->delete();

        return response()->json(['massage' => 'success'], 200);
    }
}