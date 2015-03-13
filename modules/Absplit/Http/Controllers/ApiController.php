<?php namespace Modules\Absplit\Http\Controllers;

use \App\Http\Controllers\Controller;
use Modules\Absplit\Models\Absplit_Experiments;

class ApiController extends Controller
{
    static function get_code()
    {
        // URL MATCH
        // TODO MOVE TO MONGO
	echo 'test'; die;
        $experiment = \Model_Absplit_Experiment::query()
            ->related('absplit_experiment_datum')
            ->where('user_id', \Controller_Rest::$user_id)
            ->where('url', trim($_SERVER['HTTP_REFERER'], '/'))
            ->where('active', 1)
            ->get_one();

        if(empty($experiment) === false)
        {
            // TODO
            // We need to check to see which vaiation they should get
            if(isset($experiment->absplit_experiment_datum->js) === true)
            {
                $experiment_data = json_decode($experiment->absplit_experiment_datum->js);
                $variation = 1;
                $js_code = [];
                foreach($experiment_data->$variation as $variation => $data)
                {
                    $js_code['data'][] = reset($data)->apply_function;
                }
                $js_code['function'] = 'absplit';
                return $js_code;
            }
        }
    }
}