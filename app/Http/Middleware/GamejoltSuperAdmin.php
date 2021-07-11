<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GamejoltSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!env("GAMEJOLT_GAME_ID") || !env("GAMEJOLT_GAME_PRIVATE_KEY")) {
            redirect()->route('gj-login')->with('error', 'Gamejolt API keys is not set by the admin!');
        }
        if(!env("GAMEJOLT_USER_ID_SUPERADMIN")) {
            redirect()->route('home')->with('warning', 'Super Admin has not been set!');
        }
        if ($request->session()->get('gjid') != env("GAMEJOLT_USER_ID_SUPERADMIN")) {
            return redirect()->route('home')->with('warning', 'You do not have access to this page!');
        }
        return $next($request);
    }
}
