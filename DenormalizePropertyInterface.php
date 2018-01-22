<?php

namespace FDevs\Serializer;

interface DenormalizePropertyInterface extends PropertyInterface
{
    /**
     * @param object $object
     * @param mixed  $value
     */
    public function setValue($object, $value);

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function denormalize($value);
}
