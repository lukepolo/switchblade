<?php

namespace HeatMap;

class Controller_Site extends \Controller_Template
{
    public function action_view($user_id)
    {
        // Get an instance
        $mongodb = \Mongo_Db::instance();

        $data = new \stdClass;
        
        $mongodb = \Mongo_Db::instance();
        
        $mongodb->where(array('_id' => new \MongoId($user_id)));
        
        $data->heatmap_user = $mongodb->get_one('heatmap_users');
        
        $data->heatmap = $mongodb->get_where('heatmap', array(
            'user_id' => \Auth::get_user_id()[1],
            'user' => $user_id
        ));
        
        
        $mongodb->where(array('user_id' => $user_id));
        $data->url = $mongodb->get_one('screenshots');
        
        $this->template->content = \View::Forge('private/site/view', $data);
    }
}
        