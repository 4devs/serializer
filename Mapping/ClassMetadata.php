<?php

namespace FDevs\Serializer\Mapping;

class ClassMetadata extends PropertyMetadata implements ClassMetadataInterface
{
    /**
     * @var PropertyMetadata[]
     */
    protected $properties = [];

    /**
     * @var \ReflectionClass
     */
    protected $reflectionClass;

    /**
     * @return \ReflectionClass
     */
    public function getReflectionClass()
    {
        return $this->reflectionClass ?: $this->reflectionClass = new \ReflectionClass($this->getName());
    }

    /**
     * @param \ReflectionClass $reflectionClass
     *
     * @return $this
     */
    public function setReflectionClass($reflectionClass)
    {
        $this->reflectionClass = $reflectionClass;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function merge(MetadataInterface $classMetadata)
    {
        if (!$classMetadata instanceof ClassMetadataInterface) {
            throw new \InvalidArgumentException('$classMetadata must be an instance of ClassMetadataInterface.');
        }

        foreach ($classMetadata as $key => $item) {
            if (isset($this->properties[$key])) {
                $this->properties[$key]->merge($item);
            } else {
                $this->addPropertyMetadata($item);
            }
        }

        parent::merge($classMetadata);
    }

    /**
     * {@inheritdoc}
     */
    public function addPropertyMetadata(MetadataInterface $metadata)
    {
        $this->properties[$metadata->getName()] = $metadata;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPropertiesMetadata()
    {
        return $this->properties;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->properties,
            parent::serialize(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($str)
    {
        list($this->properties, $parentStr) = unserialize($str);
        parent::unserialize($parentStr);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->properties);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        next($this->properties);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->properties);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return key($this->properties) !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        reset($this->properties);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->properties);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->properties[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if (null === $offset) {
            $this->properties[] = $value;
        } else {
            $this->properties[$offset] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->properties[$offset]);
    }
}
