<?php

namespace pierresilva\CodeGenerator\Support\Contracts;

interface ChangeDetector
{
    /**
     * Checks if an object has change
     *
     * @return bool
     */
    public function hasChange();
}
