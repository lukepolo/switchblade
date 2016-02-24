<?php

namespace Modules\Absplit\Http\Controllers\API\V1;

use \App\Http\Controllers\RestController;
use Modules\Absplit\Models\Absplit_Experiments;

class AbsplitAPI extends RestController
{
    public static function getCode()
    {
	$user = \App::make('user');
        
        $experiment = Absplit_Experiments::with('data')
            ->where('url', trim($_SERVER['HTTP_REFERER'], '/'))
            ->where('active', 1)
            ->first();
            
        if(empty($experiment)) {
            // TODO
            // We need to check to see which vaiation they should get
            if(isset($experiment->data->js) === true) {
                $experiment_data = json_decode($experiment->data->js);
                $variation = 1;
                $js_code = null;
                foreach($experiment_data->$variation as $element => $cssTraits) {
                    foreach(get_object_vars($cssTraits) as $cssTrait => $data) {
                        $js_code[] = $data->apply_function;    
                    }
                }
                
                // CUSTOM JS back to the user
                return [
                    'function' => 'apply_function',
                    'data' => [
                        'function' => ' 
                            data='.json_encode($js_code).';
                            data.forEach(function(data) {
                                eval(data);
                            });
                        '
                    ]
                ];
            }
        }
        
    }
}