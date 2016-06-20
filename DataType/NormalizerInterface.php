<?php

namespace FDevs\Serializer\DataType;

interface NormalizerInterface extends TypeInterface
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
     * @param mixed $data    Data to normalize.
     * @param array $options options data type
     *
     * @return bool
     */
    public function supportsNormalization($data, array $options);
}
