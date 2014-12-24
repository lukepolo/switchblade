<?php

namespace ABSplit;

class Controller_Home extends \Controller_Template
{
    public function action_index()
    {
        $this->template->title = 'Dashboard';
       
        $this->template->content = \View::forge('home/index');
    }
}