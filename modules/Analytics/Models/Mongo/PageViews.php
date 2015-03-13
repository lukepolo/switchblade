<?php

namespace Modules\Analytics\Models\Mongo;

class PageViews extends \Moloquent
{
    protected $connection = 'mongodb';

    protected $guarded = ['_id'];

    public function domain()
    {
        return $this->belongsTo('\App\Models\Mongo\Domain');
    }
}