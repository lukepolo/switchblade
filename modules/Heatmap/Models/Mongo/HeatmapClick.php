<?php

namespace Modules\Heatmap\Models\Mongo;

class HeatmapClick extends \Moloquent
{
    protected $connection = 'mongodb';

    protected $guarded = ['_id'];
}