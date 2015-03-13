<?php

namespace Modules\Heatmap\Models\Mongo;

class HeatmapUsers extends \Moloquent
{
    protected $connection = 'mongodb';

    protected $guarded = ['_id'];
}