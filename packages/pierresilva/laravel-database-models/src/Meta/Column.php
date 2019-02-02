<?php

namespace pierresilva\DatabaseModels\Meta;

interface Column
{
    /**
     * @return \Illuminate\Support\Fluent
     */
    public function normalize();
}
