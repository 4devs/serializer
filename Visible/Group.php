<?php

namespace FDevs\Serializer\Visible;

use FDevs\Serializer\Option\VisibleInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FDevs\Serializer\Visibility\Group as Visibility;

class Group extends Visibility implements VisibleInterface
{
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
            ->setRequired('groups')
            ->setAllowedTypes('groups', ['array'])
            ->setDefined(['key', 'required'])
            ->setDefaults([
                'key' => 'groups',
                'required' => false,
            ])
            ->setAllowedTypes('key', ['string'])
            ->setAllowedTypes('required', ['boolean']);
    }
}
