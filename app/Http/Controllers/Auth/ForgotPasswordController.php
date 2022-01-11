<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use Illuminate\Http\Request;
use App\User;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Response;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function ResetPassword(Request $request)
    {
        $data = $request->all();

        $nuevopassword = str_random(8);

        $user = User::where("username",$data['login'])->orWhere('email', $data['login'])->get()->first();
        $user->update(['password' => bcrypt($nuevopassword) ]);

        //return $nuevopassword;

        Mail::to($user->email)->queue(new ResetPassword($user, $nuevopassword));
        return Response::json(['result' => 'ok']);
           
    }
}
