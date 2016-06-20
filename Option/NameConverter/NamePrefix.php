<?php

namespace FDevs\Serializer\Option\NameConverter;

use FDevs\Serializer\Option\NameConverterInterface;

class NamePrefix implements NameConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function convert($propertyName, array $options, $type = self::TYPE_NORMALIZE)
    {
        return implode('', $options).$propertyName;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'name-prefix';
    }
}
