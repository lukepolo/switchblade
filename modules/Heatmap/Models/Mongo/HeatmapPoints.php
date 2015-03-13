<?php

namespace Modules\Heatmap\Models\Mongo;

class HeatmapPoints extends \Moloquent
{
    protected $connection = 'mongodb';

    protected $guarded = ['_id'];
}