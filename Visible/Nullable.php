<?php

namespace FDevs\Serializer\Visible;

use FDevs\Serializer\Option\AbstractOption;
use FDevs\Serializer\Option\VisibleInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Nullable extends AbstractOption implements VisibleInterface
{
    /**
     * {@inheritdoc}
     */
    public function isVisible($propertyName, $value, array $options, array $context)
    {
        return is_null($value) ? $options['nullable'] : true;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(['nullable'])
            ->setDefaults(['nullable' => false])
            ->setAllowedTypes('nullable', ['boolean']);
    }
}
