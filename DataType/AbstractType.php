<?php

namespace FDevs\Serializer\DataType;

use FDevs\Serializer\Option\AbstractOption;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractType extends AbstractOption implements DenormalizerInterface, NormalizerInterface
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
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, array $options)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return substr(parent::getName(), 0, -5);
    }
}
