<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Kiểm tra an toàn: Đã đăng nhập VÀ có thuộc tính role VÀ role == 'admin'
        if ($user && isset($user->role) && $user->role === 'admin') {
            return $next($request);
        }

        // Nếu không phải Admin thì đẩy về trang chủ kèm thông báo
        return redirect('/')->with('error', 'Bạn không có quyền truy cập trang Admin!');
    }
}