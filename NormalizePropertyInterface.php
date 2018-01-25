<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer;

interface NormalizePropertyInterface extends PropertyInterface
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isVisibleValue($value);

    /**
     * @return bool
     */
    public function isVisible();

    /**
     * @param object $object
     *
     * @return mixed
     */
    public function getValue($object);

    /**
     * @param mixed $value
     *
     * @return string|array
     */
    public function normalize($value);
}
