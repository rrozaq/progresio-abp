<?php
namespace App\Http\Controllers\v1\Startup;

// use App\Http\Resources\IncubatorResource;
use App\Http\Resources\StartupResource;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
// use App\Models\Incubator;
use App\Models\Startup_profile;
use App\Models\Startup;
use Auth;
use Illuminate\Support\Str;


class StartupController extends Controller
{
    public function getStartup()
    {
        return StartupResource::collection(Startup::with('startup_profile')->where('incubator_id', Auth::guard('startup')->user()->incubator_id)->get());
    }

    public function show()
    {
        return StartupResource::collection(Startup::with('startup_profile')->where('id', Auth::guard('startup')->user()->id)->get());
    }

    public function update(Request $request)
    {  
        Startup::where('id', Auth::guard('startup')->id())
        ->update([
            'name' => $request->name,
            'slug'  => Str::slug($request->name, '-'),
            'email' => $request->email,
        ]);

        $getprofile = Startup_profile::where('startup_id', Auth::guard('startup')->user()->id)->first();
        
        if($request->hasFile('logo')){
            $destination_path = './uploads/logo/';
            $this->validate($request, [
                'logo' => 'required|mimes:jpg,jpeg,png|max:1048',
            ]);
    
            $fileNameLogo = time().'.'.$request->logo->extension();  
            $request->logo->move($destination_path, $fileNameLogo);
        }else{
            $fileNameLogo = $getprofile->logo;
        }

        if($request->hasFile('profile_foto')){
            $destination_path = './uploads/profile/';
            $this->validate($request, [
                'profile_foto' => 'required|mimes:jpg,jpeg,png|max:1048',
            ]);
    
            $profile_foto_manager = time().'.'.$request->profile_foto->extension();
            $request->profile_foto->move($destination_path, $profile_foto_manager);
        }else{
            $profile_foto_manager = $getprofile->manager_profile_foto;
        }

        Startup_profile::updateOrCreate(
            [
                'startup_id' => Auth::guard('startup')->id()
            ],
            [
                'lokasi' => $request->lokasi,
                'phone' => $request->phone,
                'website' => $request->website,
                'instagram' => $request->instagram,
                'facebook' => $request->facebook,
                'youtube' => $request->youtube,
                'users' => $request->users,
                'employe' => $request->employe,
                'since' => $request->since,
                'logo' => $fileNameLogo,
                'about' => $request->about,
                'manager_name' => $request->manager_name,
                'manager_profile_foto' => $profile_foto_manager,
            ]
        );

        return $this->show(Auth::guard('startup')->id());
    }

}