<?php

namespace Modules\Heatmap\Models\Mongo;

class HeatmapUser extends \Moloquent
{
    protected $connection = 'mongodb';

    protected $guarded = ['_id'];

    public function HeatmapPoints()
    {
	return $this->hasMany('\Modules\Heatmap\Models\Mongo\HeatmapPoint');
    }
}