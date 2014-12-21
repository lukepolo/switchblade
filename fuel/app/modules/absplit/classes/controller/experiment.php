<?php

namespace ABSplit;

class Controller_Experiment extends \Controller_Template
{
    public function action_new()
    {
        if(\Input::Post())
        {
            $experiment = \Model_Absplit_Experiment::Forge(array(
                'user_id' => \Auth::get('id'),
                'url' => \Input::post('url'),
                'absplit_experiment_type_id' => 1,
                'active' => false,
                'absplit_experiment_data_id' => 1
            ));
            
            $experiment->save();
            
            \Debug::dump($experiment);
            die;
        }
    }
}