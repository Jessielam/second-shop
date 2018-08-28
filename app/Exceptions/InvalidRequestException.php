<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

// 用户错误行为异常
class InvalidRequestException extends Exception
{
    protected $message;
    protected $code;

    public function __construct(string $message = '', int $code = 400)
    {
        $this->message = $message;
        $this->code = $code;
        parent::__construct($message, $code);
    }

    // 该异常被触发是，系统会调用render方法进行输出，如果是AJAX请求则返回json数据，否则返回一个错误页面
    public function render(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json(['msg' => $this->message, 'code' => $this->code]);
        }

        return view('pages.error', ['msg' => $this->msg]);
    }
}
