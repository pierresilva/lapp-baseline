<?php

namespace pierresilva\InverseSeed\Facades;

use Illuminate\Support\Facades\Facade;

class InverseSeed extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'InverseSeed';
    }
}
