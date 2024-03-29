<?php

namespace Modules\Heatmap\Models\Mongo;

class HeatmapUrl extends \Moloquent
{
    use \App\Models\Traits\ByUser;

    protected $connection = 'mongodb';

    protected $guarded = ['_id'];

    public function HeatmapPoints()
    {
	return $this->hasMany('\Modules\Heatmap\Models\Mongo\HeatmapPoint');
    }
}