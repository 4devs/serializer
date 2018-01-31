<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Mapping;

interface NameConverterInterface
{
    /**
     * Converts a property name by type to value.
     *
     * @param string $propertyName name on data
     * @param array  $context
     *
     * @return string
     */
    public function convert($value, string $propertyName, array $context = []): string;
}
