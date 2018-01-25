<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Option;

use FDevs\Serializer\OptionInterface;

interface AccessorInterface extends OptionInterface
{
    /**
     * @param mixed $object
     * @param array $options
     *
     * @return mixed
     */
    public function getValue($object, array $options = [], array $context = []);

    /**
     * @param mixed $object
     * @param mixed $value
     * @param array $options
     */
    public function setValue(&$object, $value, array $options = [], array $context = []);
}
