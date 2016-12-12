<?php

namespace FDevs\Serializer\Visible;

use FDevs\Serializer\Option\VisibleInterface;
use FDevs\Serializer\Visibility\Version as Visibility;

class Version extends Visibility implements VisibleInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'version';
    }

    /**
     * {@inheritdoc}
     */
    public function isVisible($propertyName, $value, array $options, array $context)
    {
        return parent::isVisibleProperty($propertyName, $options, $context);
    }
}
