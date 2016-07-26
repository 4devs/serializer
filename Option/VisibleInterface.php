<?php

namespace FDevs\Serializer\Option;

interface VisibleInterface extends OptionInterface
{
    /**
     * check shows value.
     *
     * @param string $propertyName
     * @param array  $options      property config in mapping
     * @param array  $context      Context options
     *
     * @return bool
     */
    public function isShow($propertyName, array $options, array $context);
}
