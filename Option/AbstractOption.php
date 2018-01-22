<?php

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
