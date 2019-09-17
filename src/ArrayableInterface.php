<?php

namespace Forte\Stdlib;

/**
 * Interface ArrayableInterface.
 *
 * @package Forte\Stdlib
 */
interface ArrayableInterface
{
    /**
     * Return an array representation of the implementing instance.
     *
     * @return array
     */
    public function toArray(): array;
}
