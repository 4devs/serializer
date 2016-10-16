<?php

namespace FDevs\Serializer\Mapping;

interface MetadataInterface extends \Serializable
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getOptions();

    /**
     * Merges a {@link MetadataInterface} in the current one.
     *
     * @param MetadataInterface $metadata
     */
    public function merge(MetadataInterface $metadata);
}
