<?php

namespace App\Http\Controllers;

use Exception;
use App\Exceptions\InvalidRequestException;
use App\Models\User;
use Cache;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function verify(Request $request)
    {
        $email = $request->input('email');
        $token = $request->input('token');

        if (!$email || !$token) {
            throw new InvalidRequestException('验证链接不正确');
        }

        if ($token != Cache::get('email_verification_'.$email)) {
            throw new InvalidRequestException('验证链接不正确或者已经过期');
        }

        if (!$user = User::where('email', $email)->first()) {
            throw new InvalidRequestException('非法操作，用户不存在');
        }

        Cache::forget('email_verification_'.$eamil);
        $user->update(['email_verified' => true]);

        // 最后告知用户邮件验证成功
        return view('pages.success', ['msg' => '邮件验证成功']);
    }

    // 用户手动发送验证邮箱
    public function send(Request $request)
    {
        $user = $request->user();
        // 先判断用户是否已经激活
        if ($user->email_verified) {
            throw new InvalidRequestException('您的邮箱已经激活了');
        }

        $user->notify(new EmailVerificationNotification());

        return view('pages.success', ['msg' => '邮件发送成功']);
    }
}
