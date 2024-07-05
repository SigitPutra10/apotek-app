<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsLogin
{
    public function handle(Request $request, Closure $next)
    {
    if (Auth::check()) {
        // kalau sudah ada riwayat login, maka dibolehkan 
        return $next($request);
    } else {
        return redirect()->route('login')->with('failed', 'Anda belum login');
    }
}

}