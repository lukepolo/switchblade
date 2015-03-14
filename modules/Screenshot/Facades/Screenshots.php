<?php

namespace Modules\Screenshot\Facades;

use Illuminate\Support\Facades\Facade;

class Screenshots extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'screenshots'; }

}
