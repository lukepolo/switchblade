<?php

namespace Modules\Heatmap\Models\Mongo;

class HeatmapPoint extends \Moloquent
{
    protected $connection = 'mongodb';

    protected $guarded = ['_id'];
}