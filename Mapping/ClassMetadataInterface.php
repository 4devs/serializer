<?php

namespace FDevs\Serializer\Mapping;

interface ClassMetadataInterface extends MetadataInterface, \Iterator, \ArrayAccess
{
    /**
     * @return \ReflectionClass
     */
    public function getReflectionClass();

    /**
     * Adds an {@link MetadataInterface}.
     *
     * @param MetadataInterface $metadata
     */
    public function addPropertyMetadata(MetadataInterface $metadata);

    /**
     * Gets the list of {@link MetadataInterface}.
     *
     * @return MetadataInterface[]
     */
    public function getPropertiesMetadata();
}
