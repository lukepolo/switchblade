<?php

namespace HeatMap;

class Controller_Home extends \Controller_Template
{
    public $public_classes = array(
        'action_index',
    );
        
    public function action_index()
    {
        $this->template->content = \View::Forge('public/index');
    }
    
    public function action_dashboard()
    {
        $data = new \stdClass;
        
        $mongodb = \Mongo_Db::instance();
        
        // Get Unique domains
        $data->users = $mongodb->get_where('heatmap_users', array(
            'user_id' => \Auth::get_user_id()[1],
        ));
        
        $this->template->content = \View::Forge('private/dashboard', $data);
    }
}