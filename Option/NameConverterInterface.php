<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Option;

use FDevs\Serializer\OptionInterface;

interface NameConverterInterface extends OptionInterface
{
    /**
     * Converts a property name by type to value.
     *
     * @param string $propertyName name on data
     * @param array  $options      options in mapping
     * @param array  $context
     *
     * @return string
     */
    public function convert($propertyName, array $options, array $context = []);
}
