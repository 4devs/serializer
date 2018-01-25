<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\DataType;

use FDevs\Serializer\OptionInterface;

interface DenormalizerInterface extends OptionInterface
{
    /**
     * Denormalizes data back into an object of the given class.
     *
     * @param mixed $data    data to restore
     * @param array $options options data type
     * @param array $context options available to the denormalizer
     *
     * @return object
     */
    public function denormalize($data, array $options, array $context = []);

    /**
     * Checks whether the given class is supported for denormalization by this normalizer.
     *
     * @param mixed $data    data to denormalize from
     * @param array $options options data type
     *
     * @return bool
     */
    public function supportsDenormalization($data, array $options);
}
