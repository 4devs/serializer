<?php

namespace FDevs\Serializer\DataType;

use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, array $options, array $context = [])
    {
        return \DateTime::createFromFormat($options['format'], $data);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, array $options, array $context = [])
    {
        return $data->format($options['format']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('format', 'Y-m-d\TH:i:sP')
            ->setAllowedTypes('format', ['string'])
        ;
    }
}
