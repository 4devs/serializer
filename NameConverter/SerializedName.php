<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\NameConverter;

use FDevs\Serializer\Option\NameConverterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SerializedName implements NameConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function convert($propertyName, array $options, array $context = [])
    {
        return $options['name'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'serialized-name';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('name')
            ->setAllowedTypes('name', ['string']);
    }
}
