<?php
    abstract class Controller_Template extends \Fuel\Core\Controller_Template {
        
        public function before()
        {
            // Lets render the template
            parent::before();
            
            $data = new stdClass;
            
            $controller = str_replace('controller_', '', strtolower($this->request->route->controller));
            $data->controller = $controller;
            
            $this->template->title = 'Switch Blade | '.ucwords($controller);
            
            // Somtimes we dont want to have the content in a container, in which we can override this in the controller
            $this->template->container = 'container';
            
            $this->template->footer = View::forge('core/footer');
            $this->template->navigation = View::forge('core/navigation');
            $this->template->ribbon = View::forge('core/ribbon');
            $this->template->header = View::forge('core/header');
        }
    }