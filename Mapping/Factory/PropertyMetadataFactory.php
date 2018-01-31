<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Mapping\Factory;

use FDevs\Serializer\Exception\NoSuchAccessorException;
use FDevs\Serializer\Mapping\Guess\AccessorGuesserInterface;
use FDevs\Serializer\Mapping\Guess\GuessInterface;
use FDevs\Serializer\Mapping\Guess\TypeGuesserInterface;
use FDevs\Serializer\Mapping\Guess\TypeGuessInterface;
use FDevs\Serializer\Mapping\Metadata;
use FDevs\Serializer\Mapping\NameConverterInterface;
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
     * @var NameConverterInterface
     */
    private $nameConverter;
    /**
     * @var array|TypeGuessInterface[]
     */
    private $types = [];

    /**
     * @var array|GuessInterface[]
     */
    private $accessors = [];

    /**
     * @var AccessorGuesserInterface
     */
    private $accessorGuesser;

    /**
     * PropertyMetadataFactory constructor.
     *
     * @param TypeGuesserInterface             $typeGuesser
     * @param AccessorGuesserInterface         $accessorGuesser
     * @param PropertyAccessExtractorInterface $propertyAccessExtractor
     * @param NameConverterInterface           $nameConverter
     */
    public function __construct(
        TypeGuesserInterface $typeGuesser,
        AccessorGuesserInterface $accessorGuesser,
        PropertyAccessExtractorInterface $propertyAccessExtractor,
        NameConverterInterface $nameConverter)
    {
        $this->typeGuesser = $typeGuesser;
        $this->propertyAccessExtractor = $propertyAccessExtractor;
        $this->nameConverter = $nameConverter;
        $this->accessorGuesser = $accessorGuesser;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataFor($value, string $propertyName, array $context = []): PropertyMetadataInterface
    {
        $meta = new PropertyMetadata($this->nameConverter->convert($value, $propertyName, $context));
        $type = $this->guessType($value, $propertyName, $context);
        $accessor = $this->guessAccessor($value, $propertyName, $context);
        $meta
            ->setType(new Metadata($type->getName(), $type->getOptions()))
            ->setNullable($type->isNullable())
            ->setAccessor(new Metadata($accessor->getName(), $accessor->getOptions()));

        return $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function hasMetadataFor($value, string $propertyName, array $context = []): bool
    {
        return (is_object($value) || is_string($value))
            && $this->propertyAccessExtractor->isReadable($this->getClass($value), $propertyName, $context)
            && null !== $this->guessType($value, $propertyName, $context);
    }

    /**
     * @param object|string $value
     * @param string        $propertyName
     * @param array         $context
     *
     * @return TypeGuessInterface|null
     */
    private function guessType($value, string $propertyName, array $context)
    {
        $key = $this->getKeyPrefix($value, $context).'_'.$propertyName;
        if (empty($this->types[$key])) {
            $this->types[$key] = $this->typeGuesser->guessType($this->getClass($value), $propertyName, $context);
        }

        return $this->types[$key];
    }

    /**
     * @param object|string $value
     * @param string        $propertyName
     * @param array         $context
     *
     * @throws NoSuchAccessorException
     *
     * @return GuessInterface
     */
    private function guessAccessor($value, string $propertyName, array $context)
    {
        $key = $this->getKeyPrefix($value, $context).'_'.$propertyName;
        if (empty($this->accessors[$key])) {
            $className = $this->getClass($value);
            $this->accessors[$key] = $this->accessorGuesser->guessAccessor($className, $propertyName, $context);
            if (null === $this->accessors[$key]) {
                throw new NoSuchAccessorException($className, $propertyName, $context);
            }
        }

        return $this->accessors[$key];
    }
}
