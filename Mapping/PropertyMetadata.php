<?php

namespace FDevs\Serializer\Mapping;

class PropertyMetadata implements MetadataInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var MetadataType
     */
    protected $type;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * PropertyMetadata constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function merge(MetadataInterface $classMetadata)
    {
        array_merge_recursive($this->options, $classMetadata->getOptions());
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return PropertyMetadata
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->name,
            $this->type,
            $this->options,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list($this->name, $this->type, $this->options) = unserialize($serialized);
    }

    /**
     * @return MetadataType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param MetadataType $type
     *
     * @return PropertyMetadata
     */
    public function setType(MetadataType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     *
     * @return PropertyMetadata
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }
}
