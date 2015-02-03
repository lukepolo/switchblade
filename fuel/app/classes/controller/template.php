<?php
    abstract class Controller_Template extends \Fuel\Core\Controller_Template 
    {
	// We want the template to be the foundation so we speificy the default template of private!
        public $template = 'private';
        public function before()
        {
            // Lets render the template
            parent::before();
	    
            $data = new stdClass;
            $controller = str_replace('controller_', '', strtolower($this->request->route->controller));
            $data->controller = $controller;
            
            $this->template->set = false;
          
            // Check if public classes is an array then if the requested function is allowed.
            if (!isset($this->public_classes) or (!in_array(Request::active()->action, $this->public_classes) and !in_array('action_' . Request::active()->action, $this->public_classes)))
            {
                // They must be logged in to see a private page
                if(\Auth::Check())
                {
                    if(empty(Auth::get('apikey')) === true)
                    {
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
                    }
                    $this->template->header = View::forge('core/private/header');
                    $this->template->navigation = View::forge('core/private/navigation');
                    $this->template->ribbon = View::forge('core/private/ribbon');
                    $this->template->footer = View::forge('core/private/footer');
		    $this->template->node = View::forge('core/private/node');
                    
                    // Gets the users name
                    if(Auth::get('first_name'))
                    {
                        $this->template->set_global('first_name', Auth::get('first_name'));
                        $this->template->set_global('last_name', Auth::get('last_name'));
                    }
                    elseif(Auth::get('fullname'))
                    {
                        $fullname = explode(' ', preg_replace('/“(.*)” /', '', Auth::get('fullname')));
                        $this->template->set_global('first_name', $fullname[0]);
                        $this->template->set_global('last_name', $fullname[1]);
                    }
                    
                    // Gets the users image
                    if(Auth::get('user_image'))
                    {
                        $user_image = 'https://switchblade.io/assets/img/users/'.Auth::get('user_image');
                    }
                    else
                    {
                        if(\Auth::get('gender') == 'female')
                        {
                            $user_image = 'assets/img/avatars/female.png';
                        }
                        else
                        {
                            $user_image = 'assets/img/avatars/male.png';
                        }
                    }
                    $this->template->set_global('user_image', $user_image);

                }
                else
                {
                    // You must be logged in!
                    Session::set('error', 'You cannot enter this area unless your logged in!');
                    Response::Redirect(Uri::Create('login'));
                }
            }
            else
            {
                $procssed_route = Router::process(\Request::forge(Uri::String()));
                if(empty($procssed_route->module) === false)
                {
                    $this->template = View::forge($procssed_route->module.'::public/template');
                }
                else
                {
                    $this->template = View::forge('public');
                    $this->template->header = View::forge('core/public/header');
                    $this->template->footer = View::forge('core/public/footer');
                }
                 
                $this->template->title = 'Switch Blade ';
                $this->template->container = 'container';
            }
            
            $this->template->php_session_errors = View::forge('core/php_session_errors');
            
            $this->template->title = 'Switch Blade | '.ucwords(str_replace('\\',' ',$controller));
            
            // Somtimes we dont want to have the content in a container, in which we can override this in the controller
            $this->template->container = 'container';
        }

        public function after($response)
        {
            // If nothing was returned default to the template
            if ($response === null)
            {
                $response = $this->template;
            }

            return parent::after($response);
        }
    }