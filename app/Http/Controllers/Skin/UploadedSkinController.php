<?php

namespace App\Http\Controllers\Skin;

use App\Models\Skin;
use App\Models\GJUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UploadedSkinController extends Controller
{
    public function __construct()
    {
        $this->middleware(['gj.auth', 'gj.admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $skins = Skin::all();
        return view('uploaded-skin.index')->with('skins', $skins);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function destroy(Request $request, $uuid)
    {
        $request->validate([
            'reason' => ['required', 'string'],
        ]);
        $skin = Skin::where('uuid', $uuid)->first();
        if(!Storage::disk('skin')->exists($skin->path())) {
            return redirect()->route('uploaded-skins')->with('error', 'Skin was not found!');
        }
        activity()->causedBy(GJUser::where('gjid', session()->get('gjid'))->first())->withProperties(['filename' => $skin->path(), 'gjid' => $skin->user->gjid, 'reason' => $request->reason])->log('deleted');
        $skin->delete();
        Storage::disk('skin')->delete($skin->path());
        return redirect()->route('uploaded-skins')->with('success', 'Skin was successfully deleted!');
    }
}
