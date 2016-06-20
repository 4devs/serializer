<?php

namespace FDevs\Serializer\Option;

interface VisibleInterface extends OptionInterface
{
    /**
     * @param string $propertyName
     * @param array  $options
     * @param array  $context
     *
     * @return bool
     */
    public function isShow($propertyName, array $options, array $context);
}
