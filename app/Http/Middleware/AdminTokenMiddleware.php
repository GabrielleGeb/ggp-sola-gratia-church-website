<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class AdminTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Admin-Token');

        $storedToken = DB::table('settings')
            ->where('key', 'admin_token')->value('value');

        $expiresAt = DB::table('settings')
            ->where('key', 'admin_token_expires')->value('value');

        if (!$token || !$storedToken) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (!hash_equals($storedToken, $token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (!$expiresAt || now()->greaterThan($expiresAt)) {
            return response()->json(['error' => 'Token expired'], 401);
        }

        return $next($request);
    }
}