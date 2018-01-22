<?php

namespace FDevs\Serializer;

use FDevs\Serializer\Mapping\PropertyMetadataInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PropertyFactory implements PropertyFactoryInterface
{
    /**
     * @var OptionManagerInterface
     */
    private $optionManager;

    /**
     * PropertyFactory constructor.
     *
     * @param OptionManagerInterface $optionManager
     */
    public function __construct(OptionManagerInterface $optionManager)
    {
        $this->optionManager = $optionManager;
    }

    /**
     * {@inheritdoc}
     */
    public function createNormalizeProperty(PropertyMetadataInterface $metadata, array $context = [], NormalizerInterface $normalizer = null)
    {
        $property = new NormalizeProperty($metadata, $context, $this->optionManager);
        $property->setNormalizer($normalizer);

        return $property;
    }

    /**
     * {@inheritdoc}
     */
    public function createDenormalizeProperty(PropertyMetadataInterface $metadata, array $context = [], DenormalizerInterface $denormalizer = null)
    {
        $property = new DenormalizeProperty($metadata, $context, $this->optionManager);
        $property->setDenormalizer($denormalizer);

        return $property;
    }
}
