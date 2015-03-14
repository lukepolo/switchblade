<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileFormRequest;
use App\Http\Requests\ImageRequest;

class UserController extends Controller
{
    public function getIndex()
    {
        return view('auth.profile');
    }

    public function postIndex(ProfileFormRequest $request)
    {
        $user = \App\Models\User::find(\Auth::user()->id);
        $user->first_name = \Request::input('first_name');
        $user->last_name = \Request::input('last_name');
        $user->email = \Request::input('email');

        if(\Request::input('new_password'))
        {
            $user->password = \Hash::make(\Request::input('new_password'));
        }

        $user->save();

        return redirect()->back();
    }

    public function postImage(ImageRequest $request)
    {
        $user = \App\Models\User::find(\Auth::user()->id);
        $user->profile_img = url('img/profile_images/'.str_replace('tmp/', '', \Request::file('file')));
        $user->save();

        return response()->json(['success' => true]);
    }
}
