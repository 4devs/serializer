<?php

namespace FDevs\Serializer\Option;

interface NameConverterInterface extends OptionInterface
{
    const TYPE_DENORMALIZE = 'denormalize';
    const TYPE_NORMALIZE = 'normalize';

    /**
     * Converts a property name to its normalized value.
     *
     * @param string $propertyName
     * @param array  $options
     *
     * @return string
     */
    public function convert($propertyName, array $options, $type = self::TYPE_NORMALIZE);
}
