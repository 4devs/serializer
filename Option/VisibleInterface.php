<?php

namespace FDevs\Serializer\Option;

use FDevs\Serializer\OptionInterface;

@trigger_error('The VisibleInterface interface is deprecated since 0.1 and will be removed in 1.0. Use FDevs\Serializer\Visibility\VisibilityInterface or FDevs\Serializer\Visibility\AdvancedVisibilityInterface.', E_USER_DEPRECATED);

/**
 * Interface VisibleInterface.
 *
 * @deprecated
 */
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
