<?php

namespace FDevs\Serializer\Visibility;

use FDevs\Serializer\Option\AbstractOption;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Nullable extends AbstractOption implements AdvancedVisibilityInterface
{
    /**
     * {@inheritdoc}
     */
    public function isVisibleValue($propertyName, $value, array $options, array $context)
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
