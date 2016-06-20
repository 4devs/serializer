<?php

namespace FDevs\Serializer;

use FDevs\Serializer\DataType\BooleanType;
use FDevs\Serializer\DataType\CollectionType;
use FDevs\Serializer\DataType\DateTimeType;
use FDevs\Serializer\DataType\DoctrineType;
use FDevs\Serializer\DataType\IntegerType;
use FDevs\Serializer\DataType\ObjectType;
use FDevs\Serializer\DataType\StringType;
use FDevs\Serializer\DataType\TypeInterface;
use FDevs\Serializer\Exception\DataTypeNotFoundException;
use FDevs\Serializer\Mapping\MetadataType;
use FDevs\Serializer\Normalizer\DenormalizerAwareInterface;
use FDevs\Serializer\Normalizer\DenormalizerAwareTrait;
use FDevs\Serializer\Normalizer\NormalizerAwareInterface;
use FDevs\Serializer\Normalizer\NormalizerAwareTrait;

class DataTypeFactory
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;

    /**
     * @var ResolvedDataType[]
     */
    private $resolvedTypeList = [];

    /**
     * @var TypeInterface[]
     */
    private $typeList = [];

    /**
     * @var array
     */
    private $mappingTypeList = [
        'int' => IntegerType::class,
        'integer' => IntegerType::class,
        'bool' => BooleanType::class,
        'boolean' => BooleanType::class,
        'collection' => CollectionType::class,
        'datetime' => DateTimeType::class,
        'doctrine' => DoctrineType::class,
        'object' => ObjectType::class,
        'string' => StringType::class,
    ];

    /**
     * DataTypeRegistry constructor.
     *
     * @param TypeInterface[] $types
     */
    public function __construct(array $types = [])
    {
        foreach ($types as $type) {
            $this->addType($type);
        }
    }

    /**
     * @param MetadataType $type
     *
     * @return TypeInterface
     */
    public function getType(MetadataType $type)
    {
        return $this->getResolvedType($type)->getType();
    }

    /**
     * @param MetadataType $type
     *
     * @return array
     */
    public function resolveOptions(MetadataType $type)
    {
        return $this->getResolvedType($type)->getOptionsResolver()->resolve($type->getOptions());
    }

    /**
     * @param TypeInterface $type
     *
     * @return $this
     */
    public function addType(TypeInterface $type)
    {
        $this->typeList[get_class($type)] = $type;

        return $this;
    }

    /**
     * @param string $type
     * @param array  $options
     *
     * @return MetadataType
     *
     * @throws DataTypeNotFoundException
     */
    public function createType($type, array $options = [])
    {
        $class = isset($this->mappingTypeList[$type]) ? $this->mappingTypeList[$type] : $type;
        if (!class_exists($class)) {
            throw new DataTypeNotFoundException($type, $class);
        }

        return new MetadataType($class, $options);
    }

    /**
     * @param string $type
     * @param string $class
     *
     * @return $this
     */
    public function addMappingType($type, $class)
    {
        $this->mappingTypeList[$type] = $class;

        return $this;
    }

    /**
     * @param MetadataType $type
     *
     * @return ResolvedDataType
     */
    private function getResolvedType(MetadataType $type)
    {
        $class = $type->getName();

        if (!isset($this->resolvedTypeList[$class])) {
            if (isset($this->typeList[$class])) {
                $type = $this->typeList[$class];
                unset($this->typeList[$class]);
            } else {
                $type = new $class();
            }
            if ($type instanceof DenormalizerAwareInterface && $denormalizer = $this->getDenormalizer()) {
                $type->setDenormalizer($denormalizer);
            }
            if ($type instanceof NormalizerAwareInterface && $normalizer = $this->getNormalizer()) {
                $type->setNormalizer($normalizer);
            }
            $this->resolvedTypeList[$class] = $this->resolveType($type);
        }

        return $this->resolvedTypeList[$class];
    }

    /**
     * @param TypeInterface $type
     *
     * @return ResolvedDataType
     */
    private function resolveType(TypeInterface $type)
    {
        return new ResolvedDataType($type);
    }
}
