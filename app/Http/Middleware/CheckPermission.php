<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!$request->user()->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akses ditolak. Anda tidak memiliki izin untuk tindakan ini.'], 403);
            }
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk tindakan ini.');
        }

        return $next($request);
    }
}
