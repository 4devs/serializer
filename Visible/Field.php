<?php

namespace FDevs\Serializer\Visible;

use FDevs\Serializer\Option\VisibleInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FDevs\Serializer\Visibility\Field as Visibility;

class Field extends Visibility implements VisibleInterface
{
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
    public function isVisible($propertyName, $value, array $options, array $context)
    {
        return parent::isVisibleProperty($propertyName, $options, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(['required', 'key'])
            ->setDefaults(['required' => false, 'key' => 'fields'])
            ->setAllowedTypes('required', ['boolean'])
            ->setAllowedTypes('key', ['string']);
    }
}
