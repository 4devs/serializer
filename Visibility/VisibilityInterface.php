<?php

namespace FDevs\Serializer\Visibility;

use FDevs\Serializer\OptionInterface;

interface VisibilityInterface extends OptionInterface
{
    /**
     * @param string $propertyName
     * @param array  $options
     * @param array  $context
     *
     * @return bool
     */
    public function isVisibleProperty($propertyName, array $options, array $context);
}
