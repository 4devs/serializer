<?php

namespace FDevs\Serializer;

use FDevs\Serializer\DataType\AbstractType;
use FDevs\Serializer\Exception\OptionNotFoundException;

interface OptionRegistryInterface
{
    const TYPE_VISIBLE = 'visible';
    const TYPE_NAME_CONVERTER = 'name_converter';
    const TYPE_ACCESSOR = 'accessor';
    const TYPE_DATA_TYPE = 'type';

    /**
     * @param string $name
     *
     * @return AbstractType
     *
     * @throws OptionNotFoundException
     */
    public function getDataType($name);
}
