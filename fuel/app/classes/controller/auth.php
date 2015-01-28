<?php
class Controller_Auth extends Controller_Template
{
    public $public_classes = array(
        'action_index',
        'action_logout',
        'action_register',
        'action_callback',
        'action_forgot',
        'action_check'
    );
    
    public function action_check($type)
    {
        if($type == 'email')
        {
            $user = Input::GET('email');
        }
        else
        {
            $user = Input::GET('username');
        }
        
        $user = \Auth\Model\Auth_User::query()
            ->where('email', $user)
            ->or_where('username', $user)
            ->get_one();
            
        if(empty($user) === false)
        {
            echo 'false';
        }
        else
        {
            echo 'true';
        }
        die;
    }
    
    public function action_forgot()
    {
        if(\Auth::Check() === false)
        {
            if(Input::Method() === "POST")
            {
                $user = \Auth\Model\Auth_User::query()
                    ->where('email', Input::Post('email'))
                    ->or_where('username', Input::Post('email'))
                    ->get_one();

                if(empty($user) === false)
                {
                    // Create an instance
                    $email = Email::forge();

                    // Set the from address
                    $email->from('Support@SwitchBlade.io', '[SwitchBlade.io] The Support Guy');

                    // Set the to address
                    $email->to($user->email);

                    // Set a subject
                    $email->subject('Reset your password');

                    $data = new stdClass;
                    $data->new_password = Auth::reset_password($user->username);
                    // And set the body.
                    $email->body(View::Forge('email/forgot_password', $data));

                    if($email->send())
                    {
                        Session::set('success', 'We have sent you the email, please check within a couple of minutes.');
                        Response::redirect(Uri::Create('login'));
                    }
                    else
                    {
                        Session::set('error', 'We could not send the email, please contact '.Settings::get('helpdesk'));
                    }
                }
                else
                {
                    Session::Set('error', 'There is no username / email matching '. Input::Post('email').'!');
                }
            }
            $this->template->content = View::Forge('auth/forgot');
        }
        else
        {
            Response::Redirect(Uri::Base());
        }
    }
    
    public function action_logout()
    {
        \Auth::logout();
        Response::Redirect(Uri::Create('login'));
    }
    
    public function action_index()
    {
        if(\Auth::Check())
        {
            Response::redirect_back(Uri::Base());
        }
        elseif(\Input::Method() === 'POST')
        {
            // They want to login without a provider!
            if (Auth::login(Input::Post('email'), Input::Post('password')))
            {
                if (\Input::POST('remember', false))
                {
                    // create the remember-me cookie
                    \Auth::remember_me();
                }
                else
                {
                    // delete the remember-me cookie if present
                    \Auth::dont_remember_me();
                }
                Response::Redirect(Uri::Base());
            }
            else
            {
                // Check to see if they have a user!
                $user = \Auth\Model\Auth_User::query()
                    ->where('email', Input::Post('email'))
                    ->or_where('username', Input::Post('email'))
                    ->get_one();
                
                if(empty($user) === false)
                {
                    \Session::set('login_username', Input::Post('email'));
                    \Session::set('error', 'Invalid password!');
                }
                else
                {
                    \Session::set('login_username', Input::Post('email'));
                    \Session::set('error', 'This username / email does not exist!');
                }
                Response::Redirect(Uri::Create('login'));
            }
        }
        // Show login form
        $this->template->content = View::Forge('auth/index');
    }
    
    public function action_register($provider = null)
    {
        if(\Auth::Check())
        {
            if(empty($provider) === false)
            {
                // try to link their account
                Controller_Auth::link_provider(Auth::get_user_id()[1]);
            }
            else
            {
                Response::redirect_back(Uri::Base());
            }
        }
        elseif(empty($provider) === false)
        {
            // load Opauth, it will load the provider strategy and redirect to the provider
            \Auth_Opauth::forge();
        }
        elseif(\Input::Method() === "POST")
        {
            if(\Settings::get('registration') == false)
            {
                // TODO -- add in settings if registration is open!
                if(Input::Post('terms') !== false)
                {
                    // Check to see if they have a user!
                    $found_user = \Auth\Model\Auth_User::query()
                        ->where('email', Input::Post('email'))
                        ->or_where('username', Input::Post('username'))
                        ->get_one();
                        
                    if(empty($found_user) === false)
                    {
                        if($found_user->username == Input::Post('username'))
                        {
                            \Session::set('error', 'Username already exists!');
                        }
                        else
                        {
                            \Session::set('error', 'Email already exists!');
                        }
                        // return the post as well?
                        Response::Redirect_back(Uri::Create('login'));
                    }
                    else
                    {
                        $user_id = \Auth::create_user(
                            Input::Post('username'),
                            Input::Post('password'), // PASSWORD
                            Input::Post('email'),
                            \Config::get('application.user.default_group', 3), // DEFAULT GROUP
                            array(
                                'first_name' => Input::Post('first_name'),
                                'last_name' => Input::Post('last_name'),
                                'gender' => Input::Post('gender'),
                            )
                        );   
                        if(empty($user_id) == false)
                        {
                            Auth::force_login($user_id);
                            
                            Auth::update_user(
                                array(
                                    'apikey' => Crypt::encode(\Auth::get_user_id()[1])
                                )
                            );
                            $mongodb = \Mongo_Db::instance();
                            
                            // add their api key to mongo
                            $mongodb->insert('users', array(
                                'api_key' => \Auth::get('apikey'),
                                'user_id' => \Auth::get_user_id()[1],
                                'mods' => null
                            ));
                            
                            Response::Redirect(Uri::Base());
                        }
                        else
                        {
                            \Session::set('error', 'Internal Error, please contact the helpdesk at '. Html::anchor('http://help.bladeswitch.io'));
                        }
                    }
                }
                else
                {
                    \Session::set('error', 'You must accept the Terms and Conditions!');
                }
                // Send back an error!
                \Session::set('error', 'Internal Error, please contact the helpdesk at '. Html::anchor('http://help.bladeswitch.io'));
            }
            else
            {
                \Session::set('error', 'Sorry Registration is Disabled!');
                Response::Redirect_back(Uri::Create('login'));
            }
        }
    }
    
    public function action_callback()
    {
        // Opauth can throw all kinds of nasty bits, so be prepared
        try
        {
            // get the Opauth object
            $opauth = \Auth_Opauth::forge(false);
            
            // and process the callback
            $status = $opauth->login_or_register();
            
            // fetch the provider name from the opauth response so we can display a message
            $provider = $opauth->get('auth.provider', '?');
            
            
            // deal with the result of the callback process
            switch ($status)
            {
                // a local user was logged-in, the provider has been linked to this user
                case 'linked':
                    // inform the user the link was succesfully made
                    // and set the redirect url for this status
                     Session::set('success','You have connected your '.$provider.' account!');
                break;
            
                // the provider was known and linked, the linked account as logged-in
                case 'logged_in':
                    // inform the user the login using the provider was succesful
                    // and set the redirect url for this status
                break;
            
                // we don't know this provider login, ask the user to create a local account first
                case 'register':
                    if(\Settings::get('registration') == false)
                    {
                        // inform the user the login using the provider was succesful, but we need a local account to continue
                        // and set the redirect url for this status
                        
                        switch ($provider)
                        {
                            case 'Twitter' :
                                $user_login = $opauth->get('auth.raw.screen_name');
                                $email = $opauth->get('auth.raw.screen_name').'@twitter.com';
                            break;
                            case 'Google' :
                                $user_login = str_replace('@gmail.com','',$opauth->get('auth.raw.email'));
                                $email = $opauth->get('auth.raw.email');
                            break;
                            case 'Facebook' :
                                $user_login = $opauth->get('auth.raw.username');
                                $email = $opauth->get('auth.raw.username').'@facebook.com';
                            break;
                        }
                        
                        // call Auth to create this user
                        $found_user = \Auth\Model\Auth_User::query()
                            ->where('username', $user_login)
                            ->or_where('email', $email)
                            ->get_one();
                        
                        if(empty($found_user) === false)
                        {
                            if($found_user->email == $email)
                            {
                                // FORCE LOGIN AND REGISTER NEW AUTH
                                Auth::force_login($found_user->id);
                                Controller_Auth::link_provider($found_user->id);
                            }
                            else
                            {
                                // Username already taken
                                Session::set('error',$user_login.' , Username is already taken, please register manually or try a different account');
                                Response::Redirect(Uri::Base());
                            }
                        }
                        else
                        {
                            $user_id = \Auth::create_user(
                                $user_login,
                                md5($opauth->get('auth.credentials.token')), // PASSWORD
                                $email,
                                \Config::get('application.user.default_group', 3), // DEFAULT GROUP
                                array(
                                    'fullname' =>   $opauth->get('auth.info.name'),
                                )
                            );
                            
                            Auth::force_login($user_id);
                        }
                        
                        $opauth->login_or_register();
                        
                        Session::set('success','You have connected your '.$provider.' account!');
                    }
                    else
                    {
                        \Session::set('error', 'Sorry Registration is Disabled!');
                        Response::Redirect_back(Uri::Create('login'));
                    }
                break;
            
                // we didn't know this provider login, but enough info was returned to auto-register the user
                case 'registered':
                    // inform the user the login using the provider was succesful, and we created a local account
                    // and set the redirect url for this status
                break;
                default:
                    throw new \FuelException('Auth_Opauth::login_or_register() has come up with a result that we do not know how to handle.');
            }
            // redirect to the url set
            \Response::redirect(Uri::Base());
        }
        
        // deal with Opauth exceptions
        catch (\OpauthException $e)
        {
            Session::set('error',ucfirst($e->getMessage()).'!');
            \Response::redirect_back();
        }
        
        // catch a user cancelling the authentication attempt (some providers allow that)
        catch (\OpauthCancelException $e)
        {
            Session::set('error','It looks like you canceled your authorization.');
            \Response::redirect_back();
        }
    }
    
    public static function link_provider($user_id)
    {
        // do we have an auth strategy to match?
        if ($authentication = \Session::get('auth-strategy.authentication', array()))
        {
           // don't forget to pass false, we need an object instance, not a strategy call
           $opauth = \Auth_Opauth::forge(false);

           // call Opauth to link the provider login with the local user
           $insert_id = $opauth->link_provider(array(
               'parent_id' => $user_id,
               'provider' => $authentication['provider'],
               'uid' => $authentication['uid'],
               'access_token' => $authentication['access_token'],
               'secret' => $authentication['secret'],
               'refresh_token' => $authentication['refresh_token'],
               'expires' => $authentication['expires'],
               'created_at' => time(),
           ));
       }
    }
}