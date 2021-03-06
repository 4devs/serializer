<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @throws OptionNotFoundException
     *
     * @return AbstractType
     */
    public function getDataType($name);
}
