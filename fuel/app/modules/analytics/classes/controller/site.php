<?php

namespace Analytics;

class Controller_Site extends \Controller_Template
{
    public function action_view($site_id)
    {
        $data = new \stdClass;
        
        $mongodb = \Mongo_Db::instance();
        
        $data->analytics = $mongodb->get_where('analytics', array(
            'user_id' => \Auth::get_user_id()[1],
            'domain_id' => $site_id
        ));
        
        // Build the Days - Then we are going sort
        foreach($data->analytics as $analytic_data)
        {
            $data->analytics_data[] = $analytic_data['time'];
        }
        
        $this->template->content = \View::Forge('private/site/view', $data);
    }
}
        