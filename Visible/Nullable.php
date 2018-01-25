<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
