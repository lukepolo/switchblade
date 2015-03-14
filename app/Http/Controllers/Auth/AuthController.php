<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

// Models
use App\Models\UserProviders;

use App\Services\Settings;
class AuthController extends Controller
{

    use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
     * @return void
     */
    public function __construct(Guard $auth, Registrar $registrar)
    {
        $this->auth = $auth;
        $this->registrar = $registrar;
    }

    public function getService($provider)
    {
        if($provider == 'google')
        {
            // Super super ugly hack, but unable to extend it with the current version of socialite
            $redirect = (array) \Socialize::with($provider)->redirect();

            $url = parse_url($redirect[chr(0).'*'.chr(0).'targetUrl']);

            parse_str($url['query'], $get_array);

            $get_array['state'] =  http_build_query([
                'token' => $get_array['state'],
                'domain' => $_SERVER['HTTP_HOST']
            ]);

            // set it to the current session
            \Session::put('state', $get_array['state']);

            return new \Illuminate\Http\RedirectResponse($url['scheme'].'://'.$url['host'].$url['path'].'?'.http_build_query($get_array));
        }
        else
        {
            return \Socialize::with($provider)->redirect();
        }
    }

    public function getCallback($provider)
    {
        $user = \Socialize::with($provider)->user();
        if(empty($user) === false)
        {
            $user_provider = UserProviders::where('provider_id', '=', $user->id)
                ->where('provider', '=' , $provider)
                ->first();

            if(empty($user_provider) === false)
            {
                // force login with the user
                \Auth::login($user_provider->user);
            }
            else
            {
                $name_parts = explode(' ',  $user->getName());
                $first_name = array_shift($name_parts);
                $last_name = array_pop($name_parts);

                // Create User
                $user = $this->registrar->create([
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'profile_img' => $user->getAvatar(),
                    'email' => $user->getEmail(),
                    'provider' => $provider,
                    'provider_id' => $user->id,
                    'nickname' => $user->getNickname()
                ]);
            }
        }
        return redirect(url('/'));
    }
}
