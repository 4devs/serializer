<?php

namespace FDevs\Serializer\Option\Visible;

use FDevs\Serializer\Option\VisibleInterface;

class FieldOption implements VisibleInterface
{
    const CONTEXT_KEY = 'fields';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'fields';
    }

    /**
     * {@inheritdoc}
     */
    public function isShow($propertyName, array $options, array $context)
    {
        return !isset($context[static::CONTEXT_KEY]) || !is_array($context[static::CONTEXT_KEY]) || in_array($propertyName, $context[static::CONTEXT_KEY]);
    }
}
