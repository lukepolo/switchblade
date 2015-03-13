<?php namespace Modules\Absplit\Models;

use Illuminate\Database\Eloquent\Model;

class Absplit_Experiments extends Model {

    use \App\Models\Traits\ByUser;

    protected $guarded = [
	'id',
	'absplit_experiment_type_id',
	'absplit_experiment_settings_id' ,
	'absplit_experiment_data_id'
    ];

    public function data()
    {
	return $this->hasOne('\Modules\Absplit\Models\Absplit_Experiment_Data');
    }
}
