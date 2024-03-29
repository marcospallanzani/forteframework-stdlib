<?php

declare(strict_types=1);

/*
 * This file is part of the ForteFramework Standard Library package.
 *
 * (c) Marco Spallanzani <forteframework@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @return array<mixed, mixed>
     */
    public function toArray(): array;
}
