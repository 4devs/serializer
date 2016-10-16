<?php

namespace FDevs\Serializer\Mapping;

interface PropertyMetadataInterface extends MetadataInterface
{
    /**
     * @return MetadataInterface
     */
    public function getType();

    /**
     * @return MetadataInterface[]
     */
    public function getVisible();

    /**
     * @return MetadataInterface[]
     */
    public function getNameConverter();

    /**
     * @return MetadataInterface
     */
    public function getAccessor();
}
