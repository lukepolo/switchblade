<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

// Models
use App\UserProviders;

class AuthController extends Controller {

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

	    $this->middleware('guest', ['except' => 'getLogout']);
	}

	public function getService($provider)
	{
	    if($provider == 'google')
	    {
		// Super super ugly hack, but unable to extend it with the current version of socialite
		$redirect = (array) \Socialize::with($provider)->redirect();

		$url = parse_url($redirect[chr(0).'*'.chr(0).'targetUrl']);

		parse_str($url['query'], $get_array);

		$get_array['state'] =  http_build_query(array(
		    'token' => $get_array['state'],
		    'domain' => $_SERVER['HTTP_HOST']
		));

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
                $user_provider = UserProviders::where('provider_id', '=', $user->id)->first();

                if(empty($user_provider) === false)
                {
                    // force login with the user
                    \Auth::login($user_provider->user);
                }
                else
                {
                    // Create User
                    $user = $this->registrar->create(array(
                        'name' => $user->getName(),
                        'email' => $user->getEmail(),
                        'picture' => $user->getAvatar(),
                        'provider' => $provider,
                        'provider_id' => $user->id,
			'nickname' => $user->getNickname()
                    ));
                }
            }
	    return redirect('home');
	}
}
