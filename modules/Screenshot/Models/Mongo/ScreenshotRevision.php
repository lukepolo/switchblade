<?php

namespace Modules\Screenshot\Models\Mongo;

class ScreenshotRevision extends \Moloquent
{
    protected $connection = 'mongodb';
    // Everything is guarded

    public function screenshots()
    {
	return $this->hasMany('Modules\Screenshot\Models\Mongo\Screenshot');
    }
}