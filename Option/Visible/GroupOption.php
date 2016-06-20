<?php

namespace FDevs\Serializer\Option\Visible;

use FDevs\Serializer\Option\VisibleInterface;

class GroupOption implements VisibleInterface
{
    const CONTEXT_KEY = 'groups';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'group';
    }

    /**
     * {@inheritdoc}
     */
    public function isShow($propertyName, array $options, array $context)
    {
        $show = false;
        if (!isset($context[static::CONTEXT_KEY]) || !is_array($context[static::CONTEXT_KEY]) || !count($context[static::CONTEXT_KEY])) {
            $show = true;
        } else {
            foreach ($options as $option) {
                if (in_array($option, $context[static::CONTEXT_KEY])) {
                    $show = true;
                    break;
                }
            }
        }

        return $show;
    }
}
