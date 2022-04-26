<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use App\Mail\TestMarkdown;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('bar');
    }
    public function foo() {

            if (!Gate::allows('access-admin')) {
                abort(403);
    // The user can't update the post...
}
Gate::denies('access-admin');
        return view('test.foo');
    }

    public function bar() {
        //     $user = ['email' => 'fgf@sf.fr', 'name' => 'jean leguin' ];
        // Mail::to($user['email'])->send(new TestMail($user));
Mail::to('sdgfg@sfdrf.fr')->send(new TestMarkdown);
        return view('test.bar');

    }
}
