<?php

namespace FDevs\Serializer\Visibility;

use FDevs\Serializer\Option\AbstractOption;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Field extends AbstractOption implements VisibilityInterface
{
    /**
     * {@inheritdoc}
     */
    public function isVisibleProperty($propertyName, array $options, array $context)
    {
        return $options['required'] && (!isset($context[$options['key']]) || !is_array($context[$options['key']]) || in_array($propertyName, $context[$options['key']]));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(['required', 'key'])
            ->setDefaults(['required' => true, 'key' => 'fields'])
            ->setAllowedTypes('required', ['boolean'])
            ->setAllowedTypes('key', ['string']);
    }
}
