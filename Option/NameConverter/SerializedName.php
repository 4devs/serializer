<?php

namespace FDevs\Serializer\Option\NameConverter;

use FDevs\Serializer\Option\NameConverterInterface;

class SerializedName implements NameConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function convert($propertyName, array $options, $type = self::TYPE_NORMALIZE)
    {
        return reset($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'serialized-name';
    }
}
