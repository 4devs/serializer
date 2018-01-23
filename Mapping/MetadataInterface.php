<?php

namespace FDevs\Serializer\Mapping;

interface MetadataInterface extends \Serializable
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return array
     */
    public function getOptions(): array;

    /**
     * Merges a {@link MetadataInterface} in the current one.
     *
     * @param MetadataInterface $metadata
     */
    public function merge(self $metadata);
}
