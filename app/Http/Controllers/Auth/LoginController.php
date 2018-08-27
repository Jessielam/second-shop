<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response|void
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            // NOTE:: 登录成功, 可以更新用户登录信息
            $user = $this->guard()->user();            
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * rewrite credentials, Enable login by mail or user name
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        // 如果输入的是邮箱，同样允许登录
        $field = filter_var($request->input($this->username()), FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        return [
            $field => $request->input($this->username()),
            'password' => $request->input('password')
        ];
    }

    public function username()
    {
        return 'account';
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ], [
            $this->username() . '.required' => '账号不能为空',
            $this->username() . '.string' => '账号必须是正确的字符串',
            'password.required' => '密码不能为空'
        ]);
    }
}
