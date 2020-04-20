<?php

namespace App\Http\Middleware;

use Closure;
use App\helpers\jwtAuth;

class checkjwtAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $jwt = new jwtAuth();
        if (!$jwt->checkToken($request->header('Authorization'))) {
            return response()->json(['status' => 'error', 'message' => 'usuario no indentificado'], 403);

        }
        $request->user = $jwt->checkToken($request->header('Authorization'), true);
        return $next($request);
    }
}
