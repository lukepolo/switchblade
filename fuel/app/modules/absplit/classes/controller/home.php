<?php

namespace ABSplit;

class Controller_Home extends \Controller_Template
{
    public $public_classes = array(
        'action_index',
    );
        
    public function action_index()
    {
        $this->template->content = \View::forge('public/index');
    }
    
    public function action_dashboard()
    {
        $this->template->title = 'Dashboard';
       
        $this->template->content = \View::forge('private/dashboard');
    }
}