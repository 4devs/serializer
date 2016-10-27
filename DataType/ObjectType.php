<?php

namespace FDevs\Serializer\DataType;

use FDevs\Serializer\Exception\MappingException;
use FDevs\Serializer\Normalizer\DenormalizerAwareInterface;
use FDevs\Serializer\Normalizer\DenormalizerAwareTrait;
use FDevs\Serializer\Normalizer\NormalizerAwareInterface;
use FDevs\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObjectType extends AbstractType implements DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;

    const KEY_MAX_DEPTH = 'KEY_MAX_DEPTH';

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, array $options, array $context = [])
    {
        if (!$options['data_class'] && !isset($context[$options['key']])) {
            throw new MappingException(sprintf('set "data_class" or set context with key "%s"', $options['key']));
        }
        $dataClass = $options['data_class'] ?: $context[$options['key']];

        return $this->getDenormalizer()->denormalize($data, $dataClass, $options['format'], $context);
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
            ->setDefined(['max_depth', 'format', 'data_class', 'key'])
            ->setDefaults([
                'max_depth' => 2,
                'format' => null,
                'data_class' => null,
                'key' => 'data_class',
            ])
            ->addAllowedTypes('data_class', ['string'])
            ->addAllowedTypes('key', ['string'])
            ->addAllowedTypes('max_depth', ['integer'])
            ->addAllowedTypes('format', ['integer', 'null']);
    }
}
