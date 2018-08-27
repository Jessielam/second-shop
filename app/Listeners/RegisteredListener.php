<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Auth\Events\Registered;

class RegisteredListener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // 获取刚注册的用户
        $user = $event->user;

        // 调用 notify 发送验证邮件
        $user->notify(new EmailVerificationNotification());
    }
}
