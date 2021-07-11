<?php

namespace App\Http\Controllers\Skin;

use App\Models\GJUser;
use Illuminate\Http\Request;
use Harrk\GameJoltApi\GamejoltApi;
use App\Http\Controllers\Controller;
use Harrk\GameJoltApi\GamejoltConfig;
use Illuminate\Support\Facades\Session;
use Harrk\GameJoltApi\Exceptions\TimeOutException;

class AuthGJController extends Controller
{
    public function __construct()
    {
        $this->middleware(['gj.guest'])->except('logout');
        $this->middleware(['gj.auth'])->except(['gj-login', 'index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('skin-subdomain.login');
    }

    public function login(Request $request)
    {
        if (!env("GAMEJOLT_GAME_ID") || !env("GAMEJOLT_GAME_PRIVATE_KEY")) {
            redirect()->route('gj-login')->with('error', 'Gamejolt API keys is not set by an admin!');
        }
        $request->validate([
            'username' => ['required', 'string'],
            'token' => ['required', 'string']
        ]);
        $username = $request->username;
        $token = $request->token;

        $api = new GamejoltApi(new GamejoltConfig(env("GAMEJOLT_GAME_ID"), env("GAMEJOLT_GAME_PRIVATE_KEY")));
        
        try {
            $auth = $api->users()->auth($username, $token);
        } catch (TimeOutException $e) {
           return redirect()->route('gj-login')->with('error', $e->getMessage());
        }
        
        if(filter_var($auth['response']['success'], FILTER_VALIDATE_BOOLEAN) === false) {
            $error = $auth['response']['message'];
            // Better description of username/token error
            if($error == "No such user with the credentials passed in could be found.") {
                $error = "Username and/or token is wrong.";
            }
            return redirect()->route('gj-login')->with('warning', $error)->withInput();
        }
        $user = $api->users()->fetch($username, $token);
        $id = $user['response']['users'][0]['id'];
        $avatar_url = $user['response']['users'][0]['avatar_url'];

        // Remember the user in the session
        $request->session()->put('gju', $username);
        $request->session()->put('gjt', $token);
        $request->session()->put('gjid', $id);
        $request->session()->put('gjau', $avatar_url); // Avatar url

        // Let's store the user id and username in the database, we will not store the token!
        $gjuser = GJUser::where('gjid', $id)->where('gju', $username)->first();
        // If user is not already stored, create it
        if(!$gjuser) {
            GJUser::create([
                'gjid' => $id,
                'gju' => $username,
            ]);
        }

        // Open a session for the given user
        $api->sessions()->open($username, $token);
        return redirect()->route('home')->with('success', 'You successfully logged in with Gamejolt!');
    }

    public function logout(Request $request)
    {
        
        $username = $request->session()->get('gju');
        $token = $request->session()->get('gjt');
        $api = new GamejoltApi(new GamejoltConfig(env("GAMEJOLT_GAME_ID"), env("GAMEJOLT_GAME_PRIVATE_KEY")));
        //Close the session for user
        $api->sessions()->close($username, $token);
        $request->session()->flush();
        return redirect()->route('gj-login')->with('success', 'You successfully logged out!');
    }
}
