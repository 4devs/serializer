<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Visibility;

use FDevs\Serializer\Option\AbstractOption;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Group extends AbstractOption implements VisibilityInterface
{
    /**
     * {@inheritdoc}
     */
    public function isVisibleProperty($propertyName, array $options, array $context)
    {
        return isset($context[$options['key']]) ? count(array_intersect((array) $context[$options['key']], $options['groups'])) > 0 : !$options['required'];
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
                'required' => true,
            ])
            ->setAllowedTypes('key', ['string'])
            ->setAllowedTypes('required', ['boolean']);
    }
}
