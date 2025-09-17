<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class DepartementMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $user = Auth::user();

        // Remplacer 5 par 6 pour JuryMiddleware, 7 pour ApparitoratMiddleware
        if ($user->user_type != 5) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Accès non autorisé.');
        }

        if ($user->status != 0) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Votre compte est inactif.');
        }

        return $next($request);
    }
}
