<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Visible;

use FDevs\Serializer\Option\VisibleInterface;
use FDevs\Serializer\Visibility\Group as Visibility;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
