<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Mapping;

interface ClassMetadataInterface extends MetadataInterface, \Iterator, \ArrayAccess
{
    /**
     * @return \ReflectionClass
     */
    public function getReflectionClass();

    /**
     * Adds an {@link PropertyMetadataInterface}.
     *
     * @param PropertyMetadataInterface $metadata
     */
    public function addPropertyMetadata(PropertyMetadataInterface $metadata);

    /**
     * Gets the list of {@link PropertyMetadataInterface}.
     *
     * @return PropertyMetadataInterface[]
     */
    public function getPropertiesMetadata();
}
