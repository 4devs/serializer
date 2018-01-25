<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\DataType;

use FDevs\Serializer\Exception\MappingException;
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
        if (!$options['data_class'] && !isset($context[$options['key']])) {
            throw new MappingException(sprintf('set "data_class" or set context with key "%s"', $options['key']));
        }
        $dataClass = $options['data_class'] ?: $context[$options['key']];

        foreach ($data as $key => $item) {
            $result[$key] = $this->denormalizer->denormalize($item, $dataClass, $options['format'], $context);
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
            ->setDefined(['format', 'data_class', 'key'])
            ->setDefaults([
                'format' => null,
                'data_class' => null,
                'key' => 'data_class',
            ])
            ->addAllowedTypes('key', ['string'])
            ->addAllowedTypes('data_class', ['string', 'null'])
            ->addAllowedTypes('format', ['string', 'null']);
    }
}
