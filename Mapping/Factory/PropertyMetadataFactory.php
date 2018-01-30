<?php

namespace FDevs\Serializer\Mapping\Factory;

use FDevs\Serializer\Mapping\Guess\TypeGuesserInterface;
use FDevs\Serializer\Mapping\Guess\TypeGuessInterface;
use FDevs\Serializer\Mapping\Metadata;
use FDevs\Serializer\Mapping\PropertyMetadata;
use FDevs\Serializer\Mapping\PropertyMetadataInterface;
use Symfony\Component\PropertyInfo\PropertyAccessExtractorInterface;

class PropertyMetadataFactory implements PropertyMetadataFactoryInterface
{
    use ClassResolverTrait;
    use KeyResolverTrait;

    /**
     * @var TypeGuesserInterface
     */
    private $typeGuesser;

    /**
     * @var PropertyAccessExtractorInterface
     */
    private $propertyAccessExtractor;

    /**
     * @var array|TypeGuesserInterface[]
     */
    private $types = [];

    /**
     * PropertyMetadataFactory constructor.
     * @param TypeGuesserInterface $typeGuesser
     * @param PropertyAccessExtractorInterface $propertyAccessExtractor
     */
    public function __construct(TypeGuesserInterface $typeGuesser, PropertyAccessExtractorInterface $propertyAccessExtractor)
    {
        $this->typeGuesser = $typeGuesser;
        $this->propertyAccessExtractor = $propertyAccessExtractor;
    }

    /**
     * @inheritDoc
     */
    public function getMetadataFor($value, string $propertyName, array $context = []): PropertyMetadataInterface
    {
        $meta = new PropertyMetadata($propertyName);
        $type = $this->guessType($value, $propertyName, $context);
        $meta
            ->setType(new Metadata($type->getName(), $type->getOptions()))
            ->setNullable($type->isNullable());;

        return $meta;
    }

    /**
     * @inheritDoc
     */
    public function hasMetadataFor($value, string $propertyName, array $context = []): bool
    {
        return (is_object($value) || is_string($value))
            && $this->propertyAccessExtractor->isReadable($this->getClass($value), $propertyName, $context)
            && null !== $this->guessType($value, $propertyName, $context);
    }

    /**
     * @param object|string $value
     * @param string $propertyName
     * @param array $context
     * @return TypeGuessInterface|null
     */
    private function guessType($value, string $propertyName, array $context)
    {
        $key = $this->getKeyPrefix($value, $context) . '_' . $propertyName;
        if (empty($this->types[$key])) {
            $this->types[$key] = $this->typeGuesser->guessType($this->getClass($value), $propertyName, $context);
        }

        return $this->types[$key];
    }
}