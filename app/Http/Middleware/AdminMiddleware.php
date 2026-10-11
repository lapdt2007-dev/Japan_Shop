<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isAdmin()) {
            return $next($request);
        }

        // Không phải admin -> về trang chủ kèm thông báo
        return redirect('/')->with('error', 'Bạn không có quyền truy cập trang Admin!');
    }
}
