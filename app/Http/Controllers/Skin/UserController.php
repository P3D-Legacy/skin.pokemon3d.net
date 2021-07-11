<?php

namespace App\Http\Controllers\Skin;

use App\Models\GJUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['gj.auth']);
        $this->middleware(['gj.superadmin'])->except('show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = GJUser::withTrashed()->get();
        return view('user.index')->with('users', $users);
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
    public function show($gjid)
    {
        $user = GJUser::where('gjid', $gjid)->first();
        abort_unless($user, 404);
        return view('user.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($gjid)
    {
        $user = GJUser::withTrashed()->where('gjid', $gjid)->first();
        abort_unless($user, 404);
        return view('user.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $gjid)
    {
        $request->validate([
            'is_admin' => ['required', 'boolean']
        ]);
        $user = GJUser::withTrashed()->where('gjid', $gjid)->first();
        abort_unless($user, 404);
        $user->is_admin = $request->is_admin;
        $user->save();
        return redirect()->route('users')->with('success', 'User saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
