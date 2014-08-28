<?php
    abstract class Controller_Template extends \Fuel\Core\Controller_Template {
        
        public function before()
        {
            // Lets render the template
            parent::before();
            
            $data = new stdClass;
            
            $data->controller = str_replace('controller_', '',strtolower($this->request->route->controller));
            
            $this->template->footer = View::forge('core/footer');
            $this->template->navigation = View::forge('core/navigation');
            $this->template->ribbon = View::forge('core/ribbon');
            $this->template->header = View::forge('core/header');
        }
    }