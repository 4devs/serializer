<?php

namespace FDevs\Serializer\Option;

use FDevs\Serializer\OptionInterface;

interface VisibleInterface extends OptionInterface
{
    /**
     * check shows value.
     *
     * @param string $propertyName
     * @param mixed  $value
     * @param array  $options      property config in mapping
     * @param array  $context      Context options
     *
     * @return bool
     */
    public function isVisible($propertyName, $value, array $options, array $context);
}
