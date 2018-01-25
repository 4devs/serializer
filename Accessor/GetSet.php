<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Accessor;

use FDevs\Serializer\Option\AbstractOption;
use FDevs\Serializer\Option\AccessorInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GetSet extends AbstractOption implements AccessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getValue($object, array $options = [], array $context = [])
    {
        $data = null;
        if ($options['getter']) {
            $data = $object->{$options['getter']}();
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue(&$object, $value, array $options = [], array $context = [])
    {
        if ($options['setter']) {
            $object->{$options['setter']}($value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(['getter', 'setter', 'property'])
            ->setDefaults([
                'getter' => function (Options $options) {
                    return $options['property'] ? 'get'.ucfirst($options['property']) : null;
                },
                'setter' => function (Options $options) {
                    return $options['property'] ? 'set'.ucfirst($options['property']) : null;
                },
                'property' => null,
            ])
            ->setAllowedTypes('property', ['string', 'null'])
            ->setAllowedTypes('getter', ['string', 'null'])
            ->setAllowedTypes('setter', ['string', 'null']);
    }
}
