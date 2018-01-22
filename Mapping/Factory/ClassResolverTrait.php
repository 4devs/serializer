<?php

namespace FDevs\Serializer\Mapping\Factory;

trait ClassResolverTrait
{
    /**
     * @param string|object $value
     *
     * @return string
     */
    public function getClass($value)
    {
        return ltrim(is_object($value) ? get_class($value) : $value, '\\');
    }
}
