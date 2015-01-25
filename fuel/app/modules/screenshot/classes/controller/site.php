<?php

namespace Screenshot;

class Controller_Site extends \Controller_Template
{
    public function action_view($site_id)
    {
        $data = new \stdClass;
        
        $mongodb = \Mongo_Db::instance();
        
        $data->screenshots = $mongodb->get_where('screenshots', array(
            'api_key' => \Auth::get('apikey')
        ));
        
        $this->template->content = \View::Forge('private/site/view', $data);
    }
}
        