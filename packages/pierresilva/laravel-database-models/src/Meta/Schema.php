<?php

namespace pierresilva\DatabaseModels\Meta;

interface Schema
{
    /**
     * @return \Illuminate\Database\ConnectionInterface
     */
    public function connection();

    /**
     * @return string
     */
    public function schema();

    /**
     * @return \pierresilva\DatabaseModels\Meta\Blueprint[]
     */
    public function tables();

    /**
     * @param string $table
     *
     * @return bool
     */
    public function has($table);

    /**
     * @param string $table
     *
     * @return \pierresilva\DatabaseModels\Meta\Blueprint
     */
    public function table($table);

    /**
     * @param \pierresilva\DatabaseModels\Meta\Blueprint $table
     *
     * @return array
     */
    public function referencing(Blueprint $table);
}
