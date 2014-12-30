<?php

namespace ABSplit;

class Controller_Api extends \Controller_Rest
{
    public function get_code()
    {
        // We need to generate an API Key for the user
        if(1 == 1)
        {
            $experiment = \Model_Absplit_Experiment::query()
                ->related('absplit_experiment_datum')
                ->get_one();
            
            // TODO 
            // We need to check to see which vaiation they should get
            $experiment_data = json_decode($experiment->absplit_experiment_datum->js);
            $variation = 1;
           
            return $this->response($experiment_data->$variation);
        }
    }
}