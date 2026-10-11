<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCK_SECONDS       = 60;

    // Form Đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Xử lý Đăng nhập (có giới hạn số lần thử sai theo email + IP)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Vui lòng nhập Email',
            'email.email'       => 'Email không đúng định dạng',
            'password.required' => 'Vui lòng nhập Mật khẩu',
        ]);

        $throttleKey = Str::lower($credentials['email']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_LOGIN_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Bạn đã đăng nhập sai quá nhiều lần. Vui lòng thử lại sau {$seconds} giây.",
            ]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            $default = Auth::user()->isAdmin() ? route('admin.orders.index') : '/';

            return redirect()->intended($default)->with('success', 'Đăng nhập thành công!');
        }

        RateLimiter::hit($throttleKey, self::LOCK_SECONDS);

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

    // Form Đăng ký
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Xử lý Đăng ký
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required'      => 'Vui lòng nhập họ tên',
            'email.required'     => 'Vui lòng nhập email',
            'email.unique'       => 'Email này đã được sử dụng',
            'password.required'  => 'Vui lòng nhập mật khẩu',
            'password.min'       => 'Mật khẩu phải từ 8 ký tự trở lên',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        // role không nằm trong $fillable: tài khoản mới luôn là 'customer' (mặc định của cột trong DB).
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/')->with('success', 'Đăng ký tài khoản thành công!');
    }

    // Xử lý Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Đã đăng xuất!');
    }
}
