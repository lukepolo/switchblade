<?php
namespace App\Services;

use App\Model\User;
use App\Model\UserProviders;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

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
        return Validator::make($data, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:5',
        ]);
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
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'profile_img' => $data['profile_img'],
                'email' => empty($data['email']) === false ? $data['email'] : $data['nickname'].'@'.$data['provider'].'.com',
                'password' => bcrypt((string)$data['provider_id']),
            ]);

            // create a new service profider for the user
            UserProviders::create(array(
                'user_id' => $user->id,
                'provider_id' => $data['provider_id'],
                'provider' => $data['provider'],
            ));
        }

	\Auth::login($user);
	return $user;
    }
}
