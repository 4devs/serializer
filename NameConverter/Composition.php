<?php

namespace FDevs\Serializer\NameConverter;

use FDevs\Serializer\Option\NameConverterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Composition implements NameConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function convert($propertyName, array $options, array $context = [])
    {
        return $options['prefix'].$propertyName.$options['suffix'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'composition';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(['suffix', 'prefix'])
            ->setAllowedTypes('suffix', ['string'])
            ->setAllowedTypes('prefix', ['string'])
            ->setDefaults([
                'suffix' => '',
                'prefix' => '',
            ]);
    }
}
