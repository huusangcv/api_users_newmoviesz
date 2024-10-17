<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function login(Request $request){
        $email = $request->email;
        $password = $request->password;
        if(!$email || !$password) {
            return response()->json([
                'success' => false,
                'message' => "Vui lòng nhập đầy đủ email và mật khẩu"
            ],400);
        }
        $status = Auth::attempt(['email' => $email, 'password' => $password]);
        if($status) {
            $token = $request->user()->createToken('auth');
            return response()->json([
                'success' => true,
                'token' => $token->plainTextToken,
                'message' => 'Đăng nhập thành công'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => "Email hoặc mật khẩu không chính xác"
        ],401);
    }

    public function profile(Request $request) {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ],200);
    }


    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['success' => true, 'message' => 'Email khôi phục mật khẩu đã được gửi.'])
            : response()->json(['success' => false, 'message' => 'Có lỗi xảy ra.'], 500);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation'),
            function ($user) use ($request) {
                $user->password = Hash::make($request->password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['success' => true, 'message' => 'Mật khẩu đã được khôi phục.'])
            : response()->json(['success' => false, 'message' => 'Có lỗi xảy ra.'], 500);
    }
}
