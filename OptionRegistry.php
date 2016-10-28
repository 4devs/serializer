<?php

namespace FDevs\Serializer;

use FDevs\Serializer\Accessor\GetSet;
use FDevs\Serializer\DataType\ArrayType;
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

class OptionRegistry implements OptionRegistryInterface
{
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
            'get-set' => GetSet::class,
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
            'array' => ArrayType::class,
        ],
    ];

    /**
     * @var OptionInterface[]
     */
    private $options = [];

    /**
     * {@inheritdoc}
     */
    public function getDataType($name)
    {
        return $this->getOption($name, self::TYPE_DATA_TYPE);
    }

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
        if (!isset($this->options[$name]) && class_exists($name)) {
            $name = array_search($name, $this->mapping[$type]) ?: $name;
        }
        if (!isset($this->options[$name])) {
            $option = $this->createOption($name, $type);
            $name = $option->getName();
            $this->options[$name] = $option;
            if (!isset($this->mapping[$type][$name])) {
                $this->mapping[$type][$name] = get_class($option);
            }
        }

        return $this->options[$name];
    }

    /**
     * @param OptionInterface $option
     * @param string|null     $name
     *
     * @return $this
     */
    public function addOption(OptionInterface $option, $name = null)
    {
        $this->options[$name ?: $option->getName()] = $option;
        if ($option instanceof OptionRegistryAwareInterface) {
            $option->setOptionRegistry($this);
        }

        return $this;
    }

    /**
     * @param string $name
     *
     * @return OptionInterface
     *
     * @throws OptionNotFoundException
     */
    private function createOption($name, $type)
    {
        if (isset($this->mapping[$type][$name])) {
            $class = $this->mapping[$type][$name];
            $option = new $class();
        } elseif (class_exists($name)) {
            $option = new $name();
        } else {
            throw new OptionNotFoundException($name, $type);
        }

        if ($option instanceof OptionRegistryAwareInterface) {
            $option->setOptionRegistry($this);
        }

        return $option;
    }
}
