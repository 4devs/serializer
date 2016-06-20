<?php

namespace FDevs\Serializer\DataType;

use FDevs\Serializer\Normalizer\DenormalizerAwareInterface;
use FDevs\Serializer\Normalizer\DenormalizerAwareTrait;
use FDevs\Serializer\Normalizer\NormalizerAwareInterface;
use FDevs\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObjectType extends AbstractType implements DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, array $options, array $context = [])
    {
        return $this->getDenormalizer()->denormalize($data, $options['class'], null, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, array $options, array $context = [])
    {
        return $this->getNormalizer()->normalize($data, null, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['class'])
            ->addAllowedTypes('class', ['string'])
        ;
    }
}
