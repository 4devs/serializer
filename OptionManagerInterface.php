<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer;

use FDevs\Serializer\Exception\UnsupportedDataTypeException;
use FDevs\Serializer\Mapping\MetadataInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

interface OptionManagerInterface
{
    /**
     * @param MetadataInterface $metadata
     * @param string            $name
     * @param mixed             $value
     * @param array             $context
     *
     * @return bool
     */
    public function isVisibleValue(MetadataInterface $metadata, $name, $value, array $context = []);

    /**
     * @param MetadataInterface $metadata
     * @param string            $name
     * @param array             $context
     *
     * @return bool
     */
    public function isVisibleProperty(MetadataInterface $metadata, $name, array $context = []);

    /**
     * @param MetadataInterface $convert
     * @param string            $name
     * @param array             $context
     *
     * @return string
     */
    public function convertName(MetadataInterface $convert, $name, array $context = []);

    /**
     * @param MetadataInterface $accessor
     * @param object            $object
     * @param array             $context
     *
     * @return mixed
     */
    public function getValue(MetadataInterface $accessor, $object, array $context = []);

    /**
     * @param MetadataInterface $type
     * @param $value
     * @param array                    $context
     * @param NormalizerInterface|null $normalizer
     *
     * @throws UnsupportedDataTypeException
     *
     * @return array|string|bool|int|float|null
     */
    public function normalize(MetadataInterface $type, $value, array $context = [], NormalizerInterface $normalizer = null);

    /**
     * @param MetadataInterface $accessor
     * @param object            $object
     * @param mixed             $data
     * @param array             $context
     */
    public function setValue(MetadataInterface $accessor, $object, $data, array $context = []);

    /**
     * @param MetadataInterface          $accessor
     * @param mixed                      $value
     * @param array                      $context
     * @param DenormalizerInterface|null $denormalizer
     *
     * @throws UnsupportedDataTypeException
     *
     * @return mixed
     */
    public function denormalize(MetadataInterface $accessor, $value, array $context = [], DenormalizerInterface $denormalizer = null);
}
