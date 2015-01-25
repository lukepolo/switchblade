<?php

namespace Screenshot;

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
        $mongodb = \Mongo_Db::instance();
        
        $data = new \stdClass;
        
        // Get Unique domains
        $data->domains = $mongodb->get_where('user_domains', array(
            'user_id' => \Auth::get_user_id()[1]
        ));
        
        if(count($data->domains) == 1)
        {
            // we can just redirect them to their view
            \Response::Redirect(\Uri::Create('screenshot/site/view/'.$data->domains[0]['_id']->{'$id'}));
        }
        else
        {
            $this->template->content = \View::Forge('private/dashboard', $data);
        }
    }
}