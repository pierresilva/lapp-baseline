<?php

namespace pierresilva\ModelStatus\Events;

use pierresilva\ModelStatus\Status;
use Illuminate\Database\Eloquent\Model;

class StatusUpdated
{
    /** @var \pierresilva\ModelStatus\Status|null */
    public $oldStatus;

    /** @var \pierresilva\ModelStatus\Status */
    public $newStatus;

    /** @var \Illuminate\Database\Eloquent\Model */
    public $model;

    public function __construct(?Status $oldStatus, Status $newStatus, Model $model)
    {
        $this->oldStatus = $oldStatus;

        $this->newStatus = $newStatus;

        $this->model = $model;
    }
}
