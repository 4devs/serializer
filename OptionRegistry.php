<?php

namespace FDevs\Serializer;

use FDevs\Serializer\DataType\BooleanType;
use FDevs\Serializer\DataType\CollectionType;
use FDevs\Serializer\DataType\DateTimeType;
use FDevs\Serializer\DataType\DoctrineType;
use FDevs\Serializer\DataType\FloatType;
use FDevs\Serializer\DataType\IntegerType;
use FDevs\Serializer\DataType\ObjectType;
use FDevs\Serializer\DataType\StringType;
use FDevs\Serializer\Exception\OptionNotFoundException;
use FDevs\Serializer\Accessor\Property;
use FDevs\Serializer\NameConverter\CamelCaseToSnakeCase;
use FDevs\Serializer\NameConverter\Composition;
use FDevs\Serializer\NameConverter\SerializedName;
use FDevs\Serializer\Visible\Field;
use FDevs\Serializer\Visible\Group;
use FDevs\Serializer\Visible\Nullable;
use FDevs\Serializer\Visible\Version;

class OptionRegistry
{
    const TYPE_VISIBLE = 'visible';
    const TYPE_NAME_CONVERTER = 'name_converter';
    const TYPE_ACCESSOR = 'accessor';
    const TYPE_DATA_TYPE = 'type';
    /**
     * @var array
     */
    private $mapping = [
        self::TYPE_VISIBLE => [
            'group' => Group::class,
            'fields' => Field::class,
            'version' => Version::class,
            'nullable' => Nullable::class,
        ],
        self::TYPE_NAME_CONVERTER => [
            'camel-to-snake' => CamelCaseToSnakeCase::class,
            'serialized-name' => SerializedName::class,
            'composition' => Composition::class,
        ],
        self::TYPE_ACCESSOR => [
            'property' => Property::class,
        ],
        self::TYPE_DATA_TYPE => [
            'int' => IntegerType::class,
            'integer' => IntegerType::class,
            'float' => FloatType::class,
            'bool' => BooleanType::class,
            'boolean' => BooleanType::class,
            'collection' => CollectionType::class,
            'datetime' => DateTimeType::class,
            'doctrine' => DoctrineType::class,
            'object' => ObjectType::class,
            'string' => StringType::class,
        ],
    ];

    /**
     * @var OptionInterface[]
     */
    private $options = [];

    /**
     * @param string $name
     * @param string $type
     * @param string $class
     *
     * @return $this
     */
    public function addMapping($name, $type, $class)
    {
        $this->mapping[$type][$name] = $class;

        return $this;
    }

    /**
     * @param string $name class or name
     * @param string $type
     *
     * @return OptionInterface
     *
     * @throws OptionNotFoundException
     */
    public function getOption($name, $type)
    {
        if (!isset($this->options[$name])) {
            $this->options[$name] = $this->createOption($name, $type);
        }

        return $this->options[$name];
    }

    /**
     * @param OptionInterface $option
     *
     * @return $this
     */
    public function addOption(OptionInterface $option)
    {
        $this->options[$option->getName()] = $option;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return OptionInterface
     *
     * @throws OptionNotFoundException
     */
    public function createOption($name, $type)
    {
        if (isset($this->mapping[$type][$name])) {
            $class = $this->mapping[$type][$name];
            $option = new $class();
        } elseif (class_exists($name)) {
            $option = new $name();
        } else {
            throw new OptionNotFoundException($name, $type);
        }

        return $option;
    }
}
