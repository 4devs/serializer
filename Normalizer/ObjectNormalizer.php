<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Normalizer;

use FDevs\Serializer\Exception\RuntimeException;
use FDevs\Serializer\Mapping\Factory\MetadataFactoryInterface;
use FDevs\Serializer\PropertyFactoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ObjectNormalizer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    /**
     * @var DenormalizerInterface
     */
    private $denormalizer;

    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * @var PropertyFactoryInterface
     */
    private $propertyFactory;

    /**
     * @var bool
     */
    private $strict;

    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * ObjectNormalizer constructor.
     *
     * @param MetadataFactoryInterface $metadataFactory
     * @param PropertyFactoryInterface $propertyFactory
     */
    public function __construct(MetadataFactoryInterface $metadataFactory, PropertyFactoryInterface $propertyFactory, $strict = true, LoggerInterface $logger = null)
    {
        $this->metadataFactory = $metadataFactory;
        $this->propertyFactory = $propertyFactory;
        $this->strict = $strict;
    }

    /**
     * {@inheritdoc}
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        if ($serializer instanceof DenormalizerInterface) {
            $this->denormalizer = $serializer;
        }
        if ($serializer instanceof NormalizerInterface) {
            $this->normalizer = $serializer;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $classMetadata = $this->metadataFactory->getMetadataFor($class);
        $normalizedData = [];
        $object = $this->instantiateObject($normalizedData, $class, $context);
        foreach ($classMetadata as $propertyMetadata) {
            try {
                $property = $this->propertyFactory->createDenormalizeProperty($propertyMetadata, $context, $this->denormalizer ?: $this);
                if (isset($data[$property->getName()])) {
                    $value = $property->denormalize($data[$property->getName()]);
                    $property->setValue($object, $value);
                }
            } catch (\Exception $e) {
                $this->log(LogLevel::ERROR, $e->getMessage(), ['property' => $propertyMetadata, 'context' => $context, 'object' => $object, 'format' => $format]);
                if ($this->strict) {
                    throw $e;
                }
            }
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
        foreach ($classMetadata as $propertyMetadata) {
            try {
                $property = $this->propertyFactory->createNormalizeProperty($propertyMetadata, $context, $this->normalizer ?: $this);
                if ($property->isVisible()) {
                    $value = $property->getValue($object);
                    if ($property->isVisibleValue($value)) {
                        $data[$property->getName()] = $property->normalize($value);
                    }
                }
            } catch (\Exception $e) {
                $this->log(LogLevel::ERROR, $e->getMessage(), ['property' => $propertyMetadata, 'context' => $context, 'object' => $object, 'format' => $format]);
                if ($this->strict) {
                    throw $e;
                }
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
     * @param string $level
     * @param string $message
     * @param array  $context
     */
    private function log($level, $message, array $context = [])
    {
        if ($this->logger) {
            $this->logger->log($level, $message, $context);
        }
    }

    /**
     * Instantiates an object using constructor parameters when needed.
     *
     * This method also allows to denormalize data into an existing object if
     * it is present in the context with the object_to_populate. This object
     * is removed from the context before being returned to avoid side effects
     * when recursively normalizing an object graph.
     *
     * @param array  $data
     * @param string $class
     * @param array  $context
     *
     * @throws RuntimeException
     *
     * @return object
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
}
