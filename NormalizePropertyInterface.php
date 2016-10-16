<?php

namespace FDevs\Serializer;

interface NormalizePropertyInterface extends PropertyInterface
{
    /**
     * @return bool
     */
    public function isVisible($value);

    /**
     * @param object $object
     *
     * @return mixed
     */
    public function getValue($object);

    /**
     * @param mixed $value
     *
     * @return string|array
     */
    public function normalize($value);
}
