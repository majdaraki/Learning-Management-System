<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class MustBeTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        throw_if(
            (!Auth::check() || Auth::user()->getRoleNames()[0] != 'teacher'),
            new AuthorizationException()
        );

        return $next($request);
    }
}
