<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer;

interface DenormalizePropertyInterface extends PropertyInterface
{
    /**
     * @param object $object
     * @param mixed  $value
     */
    public function setValue($object, $value);

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function denormalize($value);
}
