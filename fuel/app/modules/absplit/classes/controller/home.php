<?php

namespace ABSplit;

class Controller_Home extends \Controller_Template
{
    public function action_index()
    {
        $this->template->title = 'Dashboard';
        
        $data = new \stdClass;
        
        // TODO - Pagination
        $data->experiments = \Model_Absplit_Experiment::query()
            ->where('user_id', \Auth::get('id'))
            ->get();
        
        $this->template->content = \View::forge('home/index', $data);
    }
}