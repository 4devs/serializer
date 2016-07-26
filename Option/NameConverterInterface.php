<?php

namespace FDevs\Serializer\Option;

interface NameConverterInterface extends OptionInterface
{
    const TYPE_DENORMALIZE = 'denormalize';
    const TYPE_NORMALIZE = 'normalize';

    /**
     * Converts a property name by type to value.
     *
     * @param string $propertyName name on data
     * @param array  $options      options in mapping
     * @param string $type         type convert
     *
     * @return string
     */
    public function convert($propertyName, array $options, $type = self::TYPE_NORMALIZE);
}
