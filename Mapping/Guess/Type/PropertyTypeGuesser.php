<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Mapping\Guess\Type;

use FDevs\Serializer\DataType\ArrayType;
use FDevs\Serializer\DataType\BooleanType;
use FDevs\Serializer\DataType\CollectionType;
use FDevs\Serializer\DataType\FloatType;
use FDevs\Serializer\DataType\IntegerType;
use FDevs\Serializer\DataType\ObjectType;
use FDevs\Serializer\DataType\StringType;
use FDevs\Serializer\Mapping\Guess\Guess;
use FDevs\Serializer\Mapping\Guess\GuessInterface;
use FDevs\Serializer\Mapping\Guess\TypeGuesserInterface;
use FDevs\Serializer\Mapping\Guess\TypeGuessInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\PropertyInfo\Type;

class PropertyTypeGuesser implements TypeGuesserInterface
{
    /**
     * @var PropertyTypeExtractorInterface
     */
    private $extractor;
    /**
     * @var array
     */
    private $map = [
        Type::BUILTIN_TYPE_BOOL => BooleanType::class,
        Type::BUILTIN_TYPE_FLOAT => FloatType::class,
        Type::BUILTIN_TYPE_INT => IntegerType::class,
        Type::BUILTIN_TYPE_STRING => StringType::class,
    ];

    /**
     * PropertyTypeGuesser constructor.
     *
     * @param PropertyTypeExtractorInterface $extractor
     * @param array                          $map
     */
    public function __construct(PropertyTypeExtractorInterface $extractor, array $map = [])
    {
        $this->extractor = $extractor;
        if (!empty($map)) {
            $this->map = $map;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function guessType(string $class, string $property, array $context = []): ?TypeGuessInterface
    {
        $types = $this->extractor->getTypes($class, $property, $context);
        $guess = [];
        if (null !== $types) {
            foreach ($types as $type) {
                $builtinType = $type->getBuiltinType();
                if (isset($this->map[$builtinType])) {
                    $guess[] = new TypeGuess($this->map[$builtinType], [], $type->isNullable(), GuessInterface::HIGH_CONFIDENCE);
                } elseif (Type::BUILTIN_TYPE_OBJECT === $builtinType) {
                    $guess[] = new TypeGuess(ObjectType::class, [
                        'data_class' => $type->getClassName(),
                    ], $type->isNullable(), GuessInterface::HIGH_CONFIDENCE);
                } elseif ($type->isCollection() && null !== ($collectionValueType = $type->getCollectionValueType())) {
                    $collectionType = $collectionValueType->getBuiltinType();
                    if (isset($this->map[$collectionType])) {
                        $guess[] = new TypeGuess(ArrayType::class, [
                            'type' => $this->map[$collectionType],
                        ], $type->isNullable(), GuessInterface::HIGH_CONFIDENCE);
                    } elseif (Type::BUILTIN_TYPE_OBJECT === $collectionType) {
                        $guess[] = new TypeGuess(CollectionType::class, [
                            'data_class' => $collectionValueType->getClassName(),
                        ], $type->isNullable(), GuessInterface::HIGH_CONFIDENCE);
                    }
                }
            }
        }

        return Guess::getBestGuess($guess);
    }
}
