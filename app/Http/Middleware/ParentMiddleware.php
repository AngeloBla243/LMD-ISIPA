<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class ParentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     if(!empty(Auth::check()))
    //     {
    //         if(Auth::user()->user_type == 4)
    //         {
    //             return $next($request);
    //         }
    //         else
    //         {
    //              Auth::logout();
    //             return redirect(url(''));
    //         }
    //     }
    //     else
    //     {
    //         Auth::logout();
    //         return redirect(url(''));
    //     }

    // }

    public function handle(Request $request, Closure $next): Response
    {
        // Vérifiez si l'utilisateur est authentifié
        if (Auth::check()) {
            // Vérifiez si l'utilisateur est un étudiant (user_type == 3) et s'il est actif
            if (Auth::user()->user_type == 4 && Auth::user()->status == 0) {
                return $next($request); // L'utilisateur est actif, on continue la requête
            }
            else {
                // Si l'utilisateur n'est pas actif ou n'est pas un étudiant, on le déconnecte /inactive/{token}
                Auth::logout();
                return redirect(url(''))->with('error', 'Votre compte est inactif. Contactez l\'administrateur.');
            }
        }

        else
        {
             Auth::logout();
            return redirect(url(''));
        }

        // Si l'utilisateur n'est pas authentifié, on le redirige vers la page de connexion
        // return redirect(url(''))->with('error', 'Veuillez vous connecter.');
    }
}
