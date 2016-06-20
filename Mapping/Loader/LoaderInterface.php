<?php

namespace FDevs\Serializer\Mapping\Loader;

use FDevs\Serializer\Mapping\ClassMetadataInterface;

interface LoaderInterface
{
    /**
     * Load class metadata.
     *
     * @param ClassMetadataInterface $classMetadata A metadata
     *
     * @return bool
     */
    public function loadClassMetadata(ClassMetadataInterface $classMetadata);

    /**
     * @param string $class
     *
     * @return bool
     */
    public function hasMetadata($class);
}
