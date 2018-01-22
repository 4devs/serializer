<?php

namespace FDevs\Serializer\Visible;

use FDevs\Serializer\Option\VisibleInterface;
use FDevs\Serializer\Visibility\Nullable as Visibility;

class Nullable extends Visibility implements VisibleInterface
{
    /**
     * {@inheritdoc}
     */
    public function isVisible($propertyName, $value, array $options, array $context)
    {
        return parent::isVisibleValue($propertyName, $value, $options, $context);
    }
}
