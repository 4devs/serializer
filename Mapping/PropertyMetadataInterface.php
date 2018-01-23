<?php

namespace FDevs\Serializer\Mapping;

interface PropertyMetadataInterface extends MetadataInterface
{
    /**
     * @return MetadataInterface
     */
    public function getType(): MetadataInterface;

    /**
     * @return MetadataInterface
     */
    public function getAccessor(): MetadataInterface;

    /**
     * @return MetadataInterface[]|\iterable
     */
    public function getVisibility(): \iterable;

    /**
     * @return MetadataInterface[]|\iterable
     */
    public function getAdvancedVisibility(): \iterable;

    /**
     * @return MetadataInterface[]|\iterable
     */
    public function getNameConverter(): \iterable;

    /**
     * @return bool
     */
    public function isNullable(): bool;
}
