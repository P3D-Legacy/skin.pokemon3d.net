<?php

namespace App\Http\Controllers\Skin;

use App\Models\Skin;
use App\Models\GJUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PlayerSkinController extends Controller
{
    public function __construct()
    {
        $this->middleware(['gj.auth']);
        $this->middleware(['gj.admin'])->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $playerskins = array_filter(Storage::disk('player')->files(),
            function ($item) {return strpos($item, '.png');} // only png's
        );
        return view('player-skin.index')->with('playerskins', $playerskins);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $gjid = $request->session()->get('gjid');

        $request->validate([
            'image' => ['required', 'image', 'max:2000', 'mimes:png', 'dimensions:ratio=3/4'], // 2MB
            'rules' => ['accepted'],
        ]);
        $filename = $gjid.'.png';
        $request->file('image')->storeAs(null, $filename, 'player');

        return redirect()->route('home')->with('success', 'Skin was successfully uploaded! Not seeing it? Refresh the page again.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function duplicate(Request $request)
    {
        $gjid = $request->session()->get('gjid');
        $skincount = GJUser::find($gjid)->skins()->count();
        if($skincount >= env('SKIN_MAX_UPLOAD')) {
            return redirect()->route('skins-my')->with('warning', 'You have reached the maximum amount of skins you can upload.');
        }
        $old_filename = $gjid.'.png';
        $skin = Skin::create([
            'owner_id' => $gjid,
            'name' => 'Import: '.$gjid,
        ]);
        $new_filename = $skin->uuid.'.png';
        Storage::disk('skin')->put($new_filename, Storage::disk('player')->get($old_filename));
        return redirect()->route('skins-my')->with('success', 'Skin was duplicated!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $gjid = $request->session()->get('gjid');
        $filename = $gjid.'.png';
        if(!Storage::disk('player')->exists($filename)) {
            return redirect()->route('home')->with('error', 'Skin was not found!');
        }
        Storage::disk('player')->delete($filename);
        return redirect()->route('skins-my')->with('success', 'Skin was successfully deleted!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyAsAdmin(Request $request, $gjid)
    {
        $request->validate([
            'reason' => ['required', 'string'],
        ]);
        $filename = $gjid.'.png';
        if(!Storage::disk('player')->exists($filename)) {
            return redirect()->route('player-skins')->with('error', 'Skin was not found!');
        }
        activity()->causedBy(GJUser::where('gjid', session()->get('gjid'))->first())->withProperties(['filename' => $filename, 'gjid' => $gjid, 'reason' => $request->reason])->log('deleted');
        Storage::disk('player')->delete($filename);
        return redirect()->route('player-skins')->with('success', 'Skin was successfully deleted!');
    }
}
