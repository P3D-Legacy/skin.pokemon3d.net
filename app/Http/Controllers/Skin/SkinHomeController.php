<?php

namespace App\Http\Controllers\Skin;

use Illuminate\Http\Request;
use Harrk\GameJoltApi\GamejoltApi;
use Harrk\GameJoltApi\GamejoltConfig;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Controller;

class SkinHomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['gj.auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $api = new GamejoltApi(new GamejoltConfig(env("GAMEJOLT_GAME_ID"), env("GAMEJOLT_GAME_PRIVATE_KEY")));
        $auth = $api->users()->fetch($request->session()->get('gju'), $request->session()->get('gjt'));
        $user = null;
        if(filter_var($auth['response']['success'], FILTER_VALIDATE_BOOLEAN) === false) {
            return view('skin-subdomain.home')->with('error', $auth['response']['message']);
        }
        if(filter_var($auth['response']['success'], FILTER_VALIDATE_BOOLEAN) === true) {
            $user = $auth['response']['users'][0];
        }

        $activity = Activity::where('description' , 'deleted')->where('properties', 'LIKE', '%'.$request->session()->get('gjid').'.png%')->orWhere('properties', 'LIKE', '%gjid":'.$request->session()->get('gjid').',"reason"%')->get();

        return view('skin-subdomain.home')->with($user)->with('activity', $activity);
    }

}
