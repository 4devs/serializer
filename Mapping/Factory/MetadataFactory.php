<?php

namespace FDevs\Serializer\Mapping\Factory;

use FDevs\Serializer\Exception\NoSuchMetadataException;
use FDevs\Serializer\Mapping\ClassMetadata;
use FDevs\Serializer\Mapping\ClassMetadataInterface;
use FDevs\Serializer\Mapping\Loader\LoaderInterface;

class MetadataFactory implements MetadataFactoryInterface
{
    /**
     * The loader for loading the class metadata.
     *
     * @var LoaderInterface|null
     */
    protected $loader;

    /**
     * The loaded metadata, indexed by class name.
     *
     * @var ClassMetadataInterface[]
     */
    protected $loadedClasses = [];

    /**
     * Creates a new metadata factory.
     *
     * @param LoaderInterface $loader The loader for configuring new metadata
     *                                between multiple PHP requests
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataFor($value)
    {
        if (!is_object($value) && !is_string($value)) {
            throw new NoSuchMetadataException(sprintf('Cannot create metadata for non-objects. Got: %s', gettype($value)));
        }

        $class = ltrim(is_object($value) ? get_class($value) : $value, '\\');

        if (isset($this->loadedClasses[$class])) {
            return $this->loadedClasses[$class];
        }

        if (!class_exists($class) && !interface_exists($class)) {
            throw new NoSuchMetadataException(sprintf('The class or interface "%s" does not exist.', $class));
        }

        $metadata = new ClassMetadata($class);

        $parents = [];
        // Include constraints from the parent class
        if ($parent = $metadata->getReflectionClass()->getParentClass()) {
            $parents[] = $parent;
        }
        $parents += $metadata->getReflectionClass()->getInterfaces();

        $this->loader->loadClassMetadata($metadata);

        foreach ($parents as $parent) {
            if ($this->hasMetadataFor($parent->name)) {
                $metadata->merge($this->getMetadataFor($parent->name));
            }
        }

        return $this->loadedClasses[$class] = $metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function hasMetadataFor($value)
    {
        if (!is_object($value) && !is_string($value)) {
            return false;
        }

        $class = ltrim(is_object($value) ? get_class($value) : $value, '\\');

        return $this->loader->hasMetadata($class);
    }
}
