<?php

namespace App\Models\Mongo;

class Domain extends \Moloquent
{
    protected $connection = 'mongodb';

    protected $guarded = ['_id'];

    public function pageviews()
    {
        return $this->hasMany('Modules\Analytics\Models\Mongo\PageViews');
    }
}