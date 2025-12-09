<?php

namespace Ranken\Ectype\Traits\Enums;

trait Invokable
{
    /**
     * Make the enum case callable to return its backing value.
     * This only applies to Backed Enums.
     *
     * @return mixed
     */
    public function __invoke(): mixed
    {
        return $this->value;
    }
}
