<?php

namespace FDevs\Serializer\Normalizer;

use FDevs\Serializer\DataTypeFactory;
use FDevs\Serializer\Exception\RuntimeException;
use FDevs\Serializer\Mapping\Factory\MetadataFactoryInterface;
use FDevs\Serializer\Mapping\MetadataType;
use FDevs\Serializer\Mapping\PropertyMetadata;
use FDevs\Serializer\Option\NameConverterInterface;
use FDevs\Serializer\Option\OptionInterface;
use FDevs\Serializer\Option\VisibleInterface;
use FDevs\Serializer\DataType\TypeInterface;
use FDevs\Serializer\OptionRegistry;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use FDevs\Serializer\DataType\DenormalizerInterface as DenormalizerType;
use FDevs\Serializer\DataType\NormalizerInterface as NormalizerType;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class ObjectNormalizer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;
    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * @var OptionRegistry
     */
    private $optionRegistry;

    /**
     * @var DataTypeFactory
     */
    private $dataType;

    /**
     * ObjectNormalizer constructor.
     *
     * @param MetadataFactoryInterface $metadataFactory
     * @param DataTypeFactory|null $dataTypeFactory
     * @param OptionRegistry|null $optionRegistry
     * @param PropertyAccessorInterface|null $propertyAccessor
     */
    public function __construct(MetadataFactoryInterface $metadataFactory, DataTypeFactory $dataTypeFactory = null, OptionRegistry $optionRegistry = null, PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->metadataFactory = $metadataFactory;
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
        $this->dataType = $dataTypeFactory ?: new DataTypeFactory();
        $this->optionRegistry = $optionRegistry ?: new OptionRegistry();
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $classMetadata = $this->metadataFactory->getMetadataFor($class);
        $normalizedData = [];
        foreach ($classMetadata as $property) {
            $options = array_merge($classMetadata->getOptions(), $property->getOptions());
            $propertyName = $dataName = $property->getName();
            $dataName = $this->nameConvert($propertyName, $options);
            if (!isset($data[$dataName])) {
                continue;
            } else {
                $value = $data[$dataName];
            }
            foreach ($options as $name => $config) {
                $option = $this->getOption($name);
                if ($option instanceof VisibleInterface && !$option->isShow($propertyName, (array)$config, $context)) {
                    continue 2;
                }
            }
            $propertyType = $property->getType();
            $type = $this->getType($propertyType);
            $optionsType = $this->resolve($propertyType);
            if ($type instanceof DenormalizerType && $type->supportsDenormalization($value, $optionsType)) {
                $normalizedData[$propertyName] = $type->denormalize($value, $optionsType, $context);
            }
        }

        $object = $this->instantiateObject($normalizedData, $class, $context);
        foreach ($normalizedData as $attribute => $value) {
            $this->propertyAccessor->setValue($object, $attribute, $value);
        }

        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return class_exists($type) && $this->metadataFactory->hasMetadataFor($type);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $classMetadata = $this->metadataFactory->getMetadataFor($object);

        $data = [];
        /** @var PropertyMetadata $property */
        foreach ($classMetadata as $property) {
            $propertyName = $property->getName();
            $value = $this->propertyAccessor->getValue($object, $propertyName);
            $options = array_merge($classMetadata->getOptions(), $property->getOptions());
            $propertyName = $this->nameConvert($propertyName, $options);
            foreach ($options as $name => $config) {
                $option = $this->getOption($name);
                if ($option instanceof VisibleInterface && !$option->isShow($propertyName, (array)$config, $context)) {
                    continue 2;
                }
            }
            $propertyType = $property->getType();
            $type = $this->getType($propertyType);
            $optionsType = $this->resolve($propertyType);
            if ($type instanceof NormalizerType && $type->supportsNormalization($value, $optionsType)) {
                $data[$propertyName] = $type->normalize($value, $optionsType, $context);
            }
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return is_object($data) && $this->metadataFactory->hasMetadataFor($data);
    }

    /**
     * @param MetadataType $type
     *
     * @return TypeInterface
     */
    private function getType(MetadataType $type)
    {
        return $this->getDataType()->getType($type);
    }

    /**
     * @param string $propertyName
     * @param array $options
     * @param string $type
     * @return string
     * @throws \FDevs\Serializer\Exception\OptionNotFoundException
     */
    private function nameConvert(string $propertyName, array $options, string $type = NameConverterInterface::TYPE_DENORMALIZE): string
    {
        foreach ($options as $name => $config) {
            $option = $this->getOption($name);
            if ($option instanceof NameConverterInterface) {
                $propertyName = $option->convert($propertyName, (array)$config, $type);
            }
        }

        return $propertyName;
    }

    /**
     * @param MetadataType $type
     *
     * @return array
     */
    private function resolve(MetadataType $type)
    {
        return $this->getDataType()->resolveOptions($type);
    }

    /**
     * Instantiates an object using constructor parameters when needed.
     *
     * This method also allows to denormalize data into an existing object if
     * it is present in the context with the object_to_populate. This object
     * is removed from the context before being returned to avoid side effects
     * when recursively normalizing an object graph.
     *
     * @param array $data
     * @param string $class
     * @param array $context
     *
     * @return object
     *
     * @throws RuntimeException
     * @throws \ReflectionException
     */
    private function instantiateObject(array &$data, $class, array &$context)
    {
        if (
            isset($context[AbstractNormalizer::OBJECT_TO_POPULATE]) &&
            is_object($context[AbstractNormalizer::OBJECT_TO_POPULATE]) &&
            $context[AbstractNormalizer::OBJECT_TO_POPULATE] instanceof $class
        ) {
            $object = $context[AbstractNormalizer::OBJECT_TO_POPULATE];
            unset($context[AbstractNormalizer::OBJECT_TO_POPULATE]);

            return $object;
        }
        $reflectionClass = new \ReflectionClass($class);

        $constructor = $reflectionClass->getConstructor();
        if ($constructor) {
            $constructorParameters = $constructor->getParameters();

            $params = [];
            foreach ($constructorParameters as $constructorParameter) {
                $paramName = $constructorParameter->name;
                if (method_exists($constructorParameter, 'isVariadic') && $constructorParameter->isVariadic()) {
                    if (isset($data[$paramName]) || array_key_exists($paramName, $data)) {
                        if (!is_array($data[$paramName])) {
                            throw new RuntimeException(sprintf('Cannot create an instance of %s from serialized data because the variadic parameter %s can only accept an array.', $class, $constructorParameter->name));
                        }

                        $params = array_merge($params, $data[$paramName]);
                    }
                } elseif (isset($data[$paramName]) || array_key_exists($paramName, $data)) {
                    $params[] = $data[$paramName];
                    unset($data[$paramName]);
                } elseif ($constructorParameter->isDefaultValueAvailable()) {
                    $params[] = $constructorParameter->getDefaultValue();
                } else {
                    throw new RuntimeException(
                        sprintf(
                            'Cannot create an instance of %s from serialized data because its constructor requires parameter "%s" to be present.',
                            $class,
                            $constructorParameter->name
                        )
                    );
                }
            }

            return $reflectionClass->newInstanceArgs($params);
        }

        return new $class();
    }

    /**
     * @param $name
     * @return OptionInterface
     * @throws \FDevs\Serializer\Exception\OptionNotFoundException
     */
    private function getOption(string $name)
    {
        return $this->optionRegistry->getOption($name);
    }

    /**
     * @return DataTypeFactory
     */
    private function getDataType()
    {
        if ($this->serializer instanceof NormalizerInterface) {
            $this->dataType->setNormalizer($this->serializer);
        }
        if ($this->serializer instanceof DenormalizerInterface) {
            $this->dataType->setDenormalizer($this->serializer);
        }

        return $this->dataType;
    }
}
