<?php

namespace FDevs\Serializer\Visibility;

use FDevs\Serializer\OptionInterface;

interface AdvancedVisibilityInterface extends OptionInterface
{
    /**
     * @param string $propertyName
     * @param mixed  $value
     * @param array  $options
     * @param array  $context
     *
     * @return bool
     */
    public function isVisibleValue($propertyName, $value, array $options, array $context);
}
