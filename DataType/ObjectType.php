<?php

namespace FDevs\Serializer\DataType;

use FDevs\Serializer\Normalizer\DenormalizerAwareInterface;
use FDevs\Serializer\Normalizer\DenormalizerAwareTrait;
use FDevs\Serializer\Normalizer\NormalizerAwareInterface;
use FDevs\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObjectType extends AbstractType implements DenormalizerAwareInterface , NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;

    const KEY_MAX_DEPTH = 'KEY_MAX_DEPTH';

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, array $options, array $context = [])
    {
        return $this->getDenormalizer()->denormalize($data, $options['data_class'], $options['format'], $context);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, array $options, array $context = [])
    {
        $data = null;
        if (!isset($context[self::KEY_MAX_DEPTH]) || $context[self::KEY_MAX_DEPTH] > 0) {
            $context[self::KEY_MAX_DEPTH] = isset($context[self::KEY_MAX_DEPTH]) ? $context[self::KEY_MAX_DEPTH] - 1 : $options['max_depth'];
            $data = $this->getNormalizer()->normalize($data, $options['format'], $context);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['data_class'])
            ->setDefined(['max_depth', 'format'])
            ->setDefaults([
                'max_depth' => 2,
                'format' => null,
            ])
            ->addAllowedTypes('data_class', ['string'])
            ->addAllowedTypes('max_depth', ['integer'])
            ->addAllowedTypes('format', ['integer', 'null']);
    }
}
