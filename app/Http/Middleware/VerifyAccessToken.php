<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class VerifyAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = Http::baseUrl('http://localhost:8000')
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->withCookies([
                '_token' => $request->cookie('_token'),
            ], 'localhost')
            ->get('/api/auth/');

        switch ($response->status()) {
            case 200:
                $user = new User($response->json());

                $request->setUserResolver(function () use ($user) {
                    return $user;
                });

                Auth::setUser($user);
                break;
            case 401:
                abort(401, 'Unauthorized');
            default:
                abort(500, 'Internal Server Error');
        }

        return $next($request);
    }
}
