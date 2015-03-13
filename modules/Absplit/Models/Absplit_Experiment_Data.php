<?php namespace Modules\Absplit\Models;

use Illuminate\Database\Eloquent\Model;

class Absplit_Experiment_Data extends Model
{
    protected $table = 'absplit__experiment_data';
    public function experiment()
    {
	return $this->belongsTo('\Modules\Absplit\Models\Absplit_Experiment');
    }

}
