<?php

namespace FDevs\Serializer\DataType;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractType implements DenormalizerInterface, NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, array $options)
    {
        return !is_null($data);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, array $options)
    {
        return !is_null($data);
    }
}
