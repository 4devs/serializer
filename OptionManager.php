<?php

namespace FDevs\Serializer;

use FDevs\Serializer\DataType\NormalizerInterface as DataNormalizer;
use FDevs\Serializer\Exception\UnsupportedDataTypeException;
use FDevs\Serializer\Mapping\MetadataInterface;
use FDevs\Serializer\Normalizer\DenormalizerAwareInterface;
use FDevs\Serializer\Normalizer\NormalizerAwareInterface;
use FDevs\Serializer\Option\AccessorInterface;
use FDevs\Serializer\Option\NameConverterInterface;
use FDevs\Serializer\Visibility\AdvancedVisibilityInterface;
use FDevs\Serializer\Visibility\VisibilityInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class OptionManager implements OptionManagerInterface
{
    /**
     * @var OptionRegistry
     */
    private $optionRegistry;

    /**
     * @var array
     */
    private $option;

    /**
     * @var NameConverterInterface[]
     */
    private $convert;

    /**
     * @var AccessorInterface[]
     */
    private $accessor;

    /**
     * @var DataNormalizer[]
     */
    private $normalizer;

    /**
     * @var DenormalizerInterface[]
     */
    private $denormalizer;

    /**
     * OptionManager constructor.
     *
     * @param OptionRegistry $optionRegistry
     */
    public function __construct(OptionRegistry $optionRegistry)
    {
        $this->optionRegistry = $optionRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function isVisibleProperty(MetadataInterface $metadata, $name, array $context = [])
    {
        /** @var VisibilityInterface $visible */
        $visible = $this->optionRegistry->getOption($metadata->getName());

        return $visible->isVisibleProperty($name, $metadata->getOptions(), $context);
    }

    /**
     * {@inheritdoc}
     */
    public function isVisibleValue(MetadataInterface $metadata, $name, $value, array $context = [])
    {
        /** @var AdvancedVisibilityInterface $visible */
        $visible = $this->optionRegistry->getOption($metadata->getName());

        return $visible->isVisibleValue($name, $value, $metadata->getOptions(), $context);
    }

    /**
     * {@inheritdoc}
     */
    public function convertName(MetadataInterface $convert, $name, array $context = [])
    {
        $key = spl_object_hash($convert);
        if (!isset($this->convert[$key])) {
            $this->convert[$key] = $this->optionRegistry->getOption($convert->getName(), OptionRegistry::TYPE_NAME_CONVERTER);
            $optionResolver = new OptionsResolver();
            $this->convert[$key]->configureOptions($optionResolver);
            $this->option[OptionRegistry::TYPE_NAME_CONVERTER][$key] = $optionResolver->resolve($convert->getOptions());
        }

        return $this->convert[$key]->convert($name, $this->option[OptionRegistry::TYPE_NAME_CONVERTER][$key], $context);
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(MetadataInterface $accessor, $object, array $context = [])
    {
        $key = spl_object_hash($accessor);
        if (!isset($this->accessor[$key])) {
            $this->accessor[$key] = $this->optionRegistry->getOption($accessor->getName(), OptionRegistry::TYPE_ACCESSOR);
            $optionResolver = new OptionsResolver();
            $this->accessor[$key]->configureOptions($optionResolver);
            $this->option[OptionRegistry::TYPE_ACCESSOR][$key] = $optionResolver->resolve($accessor->getOptions());
        }

        return $this->accessor[$key]->getValue($object, $this->option[OptionRegistry::TYPE_ACCESSOR][$key], $context);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(MetadataInterface $type, $value, array $context = [], NormalizerInterface $normalizer = null)
    {
        $key = spl_object_hash($type);
        if (!isset($this->normalizer[$key])) {
            $this->normalizer[$key] = $this->optionRegistry->getDataType($type->getName());
            $optionResolver = new OptionsResolver();
            $this->normalizer[$key]->configureOptions($optionResolver);
            $this->option[OptionRegistry::TYPE_DATA_TYPE][$key] = $optionResolver->resolve($type->getOptions());
        }

        if (!$this->normalizer[$key]->supportsNormalization($value, $this->option[OptionRegistry::TYPE_DATA_TYPE][$key])) {
            throw new UnsupportedDataTypeException(gettype($value), get_class($this->normalizer[$key]));
        }

        if ($this->normalizer[$key] instanceof NormalizerAwareInterface) {
            $this->normalizer[$key]->setNormalizer($normalizer);
        }

        return $this->normalizer[$key]->normalize($value, $this->option[OptionRegistry::TYPE_DATA_TYPE][$key], $context);
    }

    /**
     * {@inheritdoc}
     */
    public function setValue(MetadataInterface $accessor, $object, $data, array $context = [])
    {
        $key = spl_object_hash($accessor);
        if (!isset($this->accessor[$key])) {
            $this->accessor[$key] = $this->optionRegistry->getOption($accessor->getName(), OptionRegistry::TYPE_ACCESSOR);
            $optionResolver = new OptionsResolver();
            $this->accessor[$key]->configureOptions($optionResolver);
            $this->option[OptionRegistry::TYPE_ACCESSOR][$key] = $optionResolver->resolve($accessor->getOptions());
        }

        return $this->accessor[$key]->setValue($object, $data, $this->option[OptionRegistry::TYPE_ACCESSOR][$key], $context);
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize(MetadataInterface $accessor, $value, array $context = [], DenormalizerInterface $denormalizer = null)
    {
        $key = spl_object_hash($accessor);
        if (!isset($this->denormalizer[$key])) {
            $this->denormalizer[$key] = $this->optionRegistry->getDataType($accessor->getName());
            $optionResolver = new OptionsResolver();
            $this->denormalizer[$key]->configureOptions($optionResolver);
            $this->option[OptionRegistry::TYPE_DATA_TYPE][$key] = $optionResolver->resolve($accessor->getOptions());
        }

        if (!$this->denormalizer[$key]->supportsDenormalization($value, $this->option[OptionRegistry::TYPE_DATA_TYPE][$key])) {
            throw new UnsupportedDataTypeException(gettype($value), get_class($this->denormalizer[$key]));
        }

        if ($this->denormalizer[$key] instanceof DenormalizerAwareInterface) {
            $this->denormalizer[$key]->setDenormalizer($denormalizer);
        }

        return $this->denormalizer[$key]->denormalize($value, $this->option[OptionRegistry::TYPE_DATA_TYPE][$key], $context);
    }
}
