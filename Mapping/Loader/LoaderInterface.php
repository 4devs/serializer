<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
