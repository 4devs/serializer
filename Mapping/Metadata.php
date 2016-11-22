<?php

namespace FDevs\Serializer\Mapping;

class Metadata implements MetadataInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * Metadata constructor.
     *
     * @param string $name
     * @param array  $options
     */
    public function __construct($name, array $options = [])
    {
        $this->name = $name;
        $this->options = $options;
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
     * @return Metadata
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * @return Metadata
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function merge(MetadataInterface $classMetadata)
    {
        $this->options = array_replace_recursive($classMetadata->getOptions(), $this->options);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->name,
            $this->options,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list($this->name, $this->options) = unserialize($serialized);
    }
}
