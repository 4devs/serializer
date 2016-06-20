<?php

namespace FDevs\Serializer;

use FDevs\Serializer\Exception\OptionNotFoundException;
use FDevs\Serializer\Option\NameConverter\CamelCaseToSnakeCase;
use FDevs\Serializer\Option\NameConverter\NamePrefix;
use FDevs\Serializer\Option\NameConverter\SerializedName;
use FDevs\Serializer\Option\OptionInterface;
use FDevs\Serializer\Option\Visible\FieldOption;
use FDevs\Serializer\Option\Visible\GroupOption;

class OptionRegistry
{
    /**
     * @var array
     */
    private $mapping = [
        'camel_to_snake' => CamelCaseToSnakeCase::class,
        'group' => GroupOption::class,
        'fields' => FieldOption::class,
        'serialized-name' => SerializedName::class,
        'name-prefix' => NamePrefix::class,
    ];

    /**
     * @var OptionInterface[]
     */
    private $options = [];

    /**
     * @param string $name class or name
     *
     * @return OptionInterface
     *
     * @throws OptionNotFoundException
     */
    public function getOption($name)
    {
        if (!isset($this->options[$name])) {
            $this->options[$name] = $this->createOption($name);
        }

        return $this->options[$name];
    }

    /**
     * @param string $name  option name
     * @param string $class class option
     *
     * @return $this
     */
    public function addMappingOption($name, $class)
    {
        $this->mapping[$name] = $class;

        return $this;
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
     * @return mixed
     *
     * @throws OptionNotFoundException
     */
    private function createOption($name)
    {
        if (isset($this->mapping[$name])) {
            $option = new $this->mapping[$name]();
        } elseif (class_exists($name)) {
            $option = new $name();
        } else {
            throw new OptionNotFoundException($name);
        }

        return $option;
    }
}
