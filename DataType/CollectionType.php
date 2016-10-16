<?php

namespace FDevs\Serializer\DataType;

use FDevs\Serializer\Normalizer\DenormalizerAwareInterface;
use FDevs\Serializer\Normalizer\DenormalizerAwareTrait;
use FDevs\Serializer\Normalizer\NormalizerAwareInterface;
use FDevs\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionType extends AbstractType implements DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, array $options, array $context = [])
    {
        $result = [];
        foreach ($data as $key => $item) {
            $result[$key] = $this->denormalizer->denormalize($item, $options['data_class'], $options['format'], $context);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, array $options, array $context = [])
    {
        $result = [];
        foreach ($data as $key => $item) {
            $result[$key] = $this->normalizer->normalize($item, $options['format'], $context);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['data_class'])
            ->setDefined(['format'])
            ->setDefaults([
                'format' => null,
            ])
            ->addAllowedTypes('data_class', ['string'])
            ->addAllowedTypes('format', ['string', 'null']);
    }
}
