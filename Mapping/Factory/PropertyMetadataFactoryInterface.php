<?php

namespace FDevs\Serializer\Mapping\Factory;

use FDevs\Serializer\Exception\NoSuchMetadataException;
use FDevs\Serializer\Mapping\PropertyMetadataInterface;

interface PropertyMetadataFactoryInterface
{
    /**
     * Returns the metadata for the given value.
     *
     * @param mixed $value Some object or class name
     * @param string $propertyName
     * @param array $context
     *
     * @throws NoSuchMetadataException If no metadata exists for the given value
     *
     * @return PropertyMetadataInterface The metadata for the value
     */
    public function getMetadataFor($value, string $propertyName, array $context = []): PropertyMetadataInterface;

    /**
     * Returns whether the property to return metadata for the given value.
     *
     * @param mixed $value
     * @param string $propertyName
     * @param array $context
     *
     * @return bool Whether metadata can be returned for that value
     */
    public function hasMetadataFor($value, string $propertyName, array $context = []): bool;

}