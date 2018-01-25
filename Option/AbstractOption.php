<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Option;

use FDevs\Serializer\OptionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractOption implements OptionInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
        $class = get_class($this);
        $name = substr($class, strripos($class, '\\') + 1);

        return strtolower(preg_replace('~(?<=\\w)([A-Z])~', '_$1', $name));
    }
}
