<?php

namespace FDevs\Serializer\DataType;

interface DenormalizerInterface extends TypeInterface
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
