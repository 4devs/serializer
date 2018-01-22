<?php

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
