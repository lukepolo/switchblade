<?php
namespace ABSplit;

class Controller_Api extends \Controller_Hybrid
{
    public static function get_code()
    {      
        // URL MATCH
        $experiment = \Model_Absplit_Experiment::query()
            ->related('absplit_experiment_datum')
            ->where('user_id', \Controller_Rest::$user_id)
            ->where('url', trim($_SERVER['HTTP_REFERER'], '/'))
            ->get_one();

        if(empty($experiment) === false)
        {
            // TODO 
            // We need to check to see which vaiation they should get
            $experiment_data = json_decode($experiment->absplit_experiment_datum->js);
            $variation = 1;

            $js_code = [];
            foreach($experiment_data->$variation as $variation => $data)
            {
                $js_code[] = reset($data)->apply_function;
            }

            return json_encode($js_code);
        }
    }
}