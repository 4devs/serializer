<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
