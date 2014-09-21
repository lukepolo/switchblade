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
        }
        
        $data = new stdClass;
        
        $data->settings = Model_Setting::query()->get();

        $this->template->content = View::forge('settings', $data);
    }
}
