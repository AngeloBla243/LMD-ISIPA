<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AcademicYear;

class SetAcademicYear
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // App\Http\Middleware\SetAcademicYear.php
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('academic_year_id')) {
            // Récupérer l'année active par défaut
            $activeYear = AcademicYear::where('is_active', 1)->first();
            session(['academic_year_id' => $activeYear->id]);
        }

        return $next($request);
    }
}
