<?php

namespace Modules\Heatmap\Models\Mongo;

class HeatmapUser extends \Moloquent
{
    protected $connection = 'mongodb';

    protected $guarded = ['_id'];
}