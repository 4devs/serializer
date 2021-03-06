<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\DataType;

use FDevs\Serializer\OptionInterface;

interface NormalizerInterface extends OptionInterface
{
    /**
     * Normalizes an object into a set of arrays/scalars.
     *
     * @param object $object  object to normalize
     * @param array  $options options data type
     * @param array  $context Context options for the normalizer
     *
     * @return array|string|bool|int|float|null
     */
    public function normalize($object, array $options, array $context = []);

    /**
     * Checks whether the given class is supported for normalization by this normalizer.
     *
     * @param mixed $data    data to normalize
     * @param array $options options data type
     *
     * @return bool
     */
    public function supportsNormalization($data, array $options);
}
