<?php
    abstract class Controller_Template extends \Fuel\Core\Controller_Template {
        
        public $template = 'template';
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
					$this->template->set = true;
					$this->template->header = View::forge('core/private/header');
					$this->template->navigation = View::forge('core/private/navigation');
					$this->template->ribbon = View::forge('core/private/ribbon');
					$this->template->footer = View::forge('core/private/footer');
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
				$this->template = View::forge('public');
				$this->template->set = true;
				$this->template->title = 'Switch Blade ';
				$this->template->container = 'container';
				$this->template->header = View::forge('core/public/header');
				$this->template->footer = View::forge('core/public/footer');
            
			}
            
            $this->template->title = 'Switch Blade | '.ucwords($controller);
            
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