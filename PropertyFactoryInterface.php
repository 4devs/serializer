<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer;

use FDevs\Serializer\Mapping\PropertyMetadataInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

interface PropertyFactoryInterface
{
    /**
     * @param PropertyMetadataInterface $metadata
     * @param array                     $context
     * @param NormalizerInterface|null  $normalizer
     *
     * @return NormalizePropertyInterface
     */
    public function createNormalizeProperty(PropertyMetadataInterface $metadata, array $context = [], NormalizerInterface $normalizer = null);

    /**
     * @param PropertyMetadataInterface  $metadata
     * @param array                      $context
     * @param DenormalizerInterface|null $denormalizer
     *
     * @return DenormalizePropertyInterface
     */
    public function createDenormalizeProperty(PropertyMetadataInterface $metadata, array $context = [], DenormalizerInterface $denormalizer = null);
}
