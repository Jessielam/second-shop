<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        return view('index.root');
    }

    public function emailVerifyNotice(Request $request)
    {
        return view('index.email_verify_notice');
    }
}
