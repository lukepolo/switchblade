<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProviders;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;
use Illuminate\Http\Exception\HttpResponseException;

class Registrar implements RegistrarContract
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
	if(\Settings::get('registration'))
	{
	    return Validator::make($data, [
		'first_name' => 'required|max:255',
		'last_name' => 'required|max:255',
		'email' => 'required|email|max:255|unique:users',
		'password' => 'required|confirmed|min:5',
	    ]);
	}
	else
	{
	    throw new HttpResponseException(redirect()->back()->withInput()->withErrors('Registration is Disabled!'));
	}
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function create(array $data)
    {

	if(isset($data['provider']) === false)
	{
	    $user = User::create([
		'first_name' => $data['first_name'],
		'last_name' => $data['last_name'],
		'email' => $data['email'],
		'password' => bcrypt($data['password']),
	    ]);
	}
	else
	{
	    \Session::flash('success', 'You successfully connected your '.ucwords($data['provider']).' account!');
	    $user = User::create([
		'first_name' => $data['first_name'],
		'last_name' => $data['last_name'],
		'profile_img' => $data['profile_img'],
		'email' => empty($data['email']) === false ? $data['email'] : $data['nickname'].'@'.$data['provider'].'.com',
		'password' => bcrypt((string)$data['provider_id']),
	    ]);

	    // create a new service profider for the user
	    UserProviders::create([
		'user_id' => $user->id,
		'provider_id' => $data['provider_id'],
		'provider' => $data['provider'],
	    ]);
	}

	$user->api_key = \Hash::make($user->id);
	$user->save();

	// add the api key to mongo , not sure why?

	\Auth::login($user);
	return $user;
    }
}
