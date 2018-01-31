<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     *
     * @deprecated
     */
    public function getNameConverter(): \iterable;

    /**
     * @return bool
     */
    public function isNullable(): bool;
}
