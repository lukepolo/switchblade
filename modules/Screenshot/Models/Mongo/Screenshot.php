<?php

namespace Modules\Screenshot\Models\Mongo;

class Screenshot extends \Moloquent
{
    use \App\Models\Traits\ByUser;

    protected $connection = 'mongodb';
    // Everything is guarded

    public function screenshot_revision()
    {
	return $this->belongsTo('Modules\Screenshot\Models\Mongo\ScreenshotRevision');
    }
}