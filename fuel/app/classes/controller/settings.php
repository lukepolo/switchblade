<?php
class Controller_Settings extends Controller_Template
{
    public function action_index()
    {
        // Ajax Request
        if(Input::Method() === 'POST')
        {
            foreach(Input::Post() as $name => $data)
            {
                $setting = Model_Setting::query()
                    ->where('name', $name)
                    ->get_one();

                if(empty($setting) === false)
                {
                    $setting->data = $data;
                    $setting->save();
                }
            }
            // Refresh the settings cache
            Cache::delete('settings');
        }
        
        $data = new stdClass;
        
        $data->settings = \Settings::get_all();
       
        $this->template->content = View::forge('settings', $data);
    }
    
    public function action_profiler()
    {
        if(Session::Get('profiler'))
        {
            Session::Delete('profiler');
        }
        else
        {
            Session::Set('profiler', true);
        }
        Response::Redirect_Back(Uri::Base());
    }
    
    public function action_minify()
    {
        if(Session::Get('minify') === false)
        {
            Session::Delete('minify');
        }
        else
        {
            Session::Set('minify', false);
        }
        Response::Redirect_Back(Uri::Base());
    }
}
