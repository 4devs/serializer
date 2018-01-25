<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
