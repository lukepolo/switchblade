<?php

namespace Modules\Tracer\Facades;

use Illuminate\Support\Facades\Facade;

class Tracer extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'tracer'; }

}
