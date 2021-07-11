<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\GJUser;
use Illuminate\Http\Request;

class GamejoltAdmin
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
        $user = GJUser::where('gjid', $request->session()->get('gjid'))->first();
        if ($user) {
            if ($user->is_admin) {
                return $next($request);
            }
        }
        return redirect()->route('home')->with('warning', 'You do not have access to this page!');
    }
}
