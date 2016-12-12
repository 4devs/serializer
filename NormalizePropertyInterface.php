<?php

namespace FDevs\Serializer;

interface NormalizePropertyInterface extends PropertyInterface
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isVisibleValue($value);

    /**
     * @return bool
     */
    public function isVisible();
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
