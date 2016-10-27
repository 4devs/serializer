<?php

namespace FDevs\Serializer;

use FDevs\Serializer\DataType\AbstractType;
use FDevs\Serializer\Exception\OptionNotFoundException;

interface OptionRegistryInterface
{
    /**
     * @param string $name
     *
     * @return AbstractType
     *
     * @throws OptionNotFoundException
     */
    public function getDataType($name);
}
