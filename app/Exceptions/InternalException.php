<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

// 系统内部异常
class InternalException extends Exception
{
    protected $message;
    protected $msgForUser;
    protected $code;

    public function __construct(string $message = '', string $msgForUser = '系统内部异常', int $code = 500)
    {
        parent::__constrnct($message, $code);
        $this->message = $message;
        $this->msgForUser = $msgForUser;
        $this->code = $code;
    }

    public function render(Request $request)
    {
        if (response()->expectsJson()) {
            return response()->json(['msg' => $this->msgForUser], $this->code);
        }

        return view('pages.error', ['msg' => $this->msgForUser]);
    }
}
