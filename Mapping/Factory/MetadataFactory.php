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

        $class = $this->getClassName($value);

        if (isset($this->loadedClasses[$class])) {
            return $this->loadedClasses[$class];
        }

        if (!class_exists($class) && !interface_exists($class) && !trait_exists($class)) {
            throw new NoSuchMetadataException(sprintf('The class or interface "%s" does not exist.', $class));
        }

        $metadata = new ClassMetadata($class);
        $r = $metadata->getReflectionClass();
        $parents = [];
        // Include constraints from the parent class
        if ($parent = $r->getParentClass()) {
            $parents[] = $parent;
        }
        $parents = array_merge($parents, $r->getInterfaces(), $r->getTraits());
        if ($this->hasMap($class)) {
            $this->loader->loadClassMetadata($metadata);
        }

        foreach ($parents as $parent) {
            if ($this->hasMap($parent->name)) {
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

        $class = $this->getClassName($value);

        $has = $this->hasMap($class);
        if (!$has) {
            $reflection = new \ReflectionClass($class);
            /** @var \ReflectionClass[] $list */
            $list = array_merge($reflection->getInterfaces(), $reflection->getTraits());
            while ($parent = $reflection->getParentClass()) {
                if ($has = $this->hasMap($parent->getName())) {
                    break;
                }
                $reflection = $parent;
            }
            if (!$has) {
                foreach ($list as $item) {
                    if ($has = $this->hasMap($item->getName())) {
                        break;
                    }
                }
            }
        }

        return $has;
    }

    /**
     * @param string|object $value
     *
     * @return string
     */
    private function getClassName($value)
    {
        return ltrim(is_object($value) ? get_class($value) : $value, '\\');
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    private function hasMap($value)
    {
        $class = $this->getClassName($value);

        return isset($this->loadedClasses[$class]) || $this->loader->hasMetadata($class);
    }
}
