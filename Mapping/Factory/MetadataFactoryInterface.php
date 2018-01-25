<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Mapping\Factory;

use FDevs\Serializer\Exception\NoSuchMetadataException;
use FDevs\Serializer\Mapping\ClassMetadataInterface;

interface MetadataFactoryInterface
{
    /**
     * Returns the metadata for the given value.
     *
     * @param mixed $value   Some object or class name
     * @param array $context
     *
     * @throws NoSuchMetadataException If no metadata exists for the given value
     *
     * @return ClassMetadataInterface The metadata for the value
     */
    public function getMetadataFor($value, array $context = []): ClassMetadataInterface;

    /**
     * Returns whether the class is able to return metadata for the given value.
     *
     * @param mixed $value
     * @param array $context
     *
     * @return bool Whether metadata can be returned for that value
     */
    public function hasMetadataFor($value, array $context = []): bool;
}
