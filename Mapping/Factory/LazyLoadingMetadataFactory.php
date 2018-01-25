<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Mapping\Factory;

use FDevs\Serializer\Exception\NoSuchMetadataException;
use FDevs\Serializer\Mapping\ClassMetadata;
use FDevs\Serializer\Mapping\ClassMetadataInterface;
use FDevs\Serializer\Mapping\Loader\LoaderInterface;
use Psr\Cache\CacheItemPoolInterface;

class LazyLoadingMetadataFactory implements MetadataFactoryInterface
{
    /**
     * The loader for loading the class metadata.
     *
     * @var LoaderInterface|null
     */
    protected $loader;

    /**
     * The cache for caching class metadata.
     *
     * @var CacheItemPoolInterface|null
     */
    protected $cache;

    /**
     * The loaded metadata, indexed by class name.
     *
     * @var ClassMetadataInterface[]
     */
    protected $loadedClasses = [];

    /**
     * Creates a new metadata factory.
     *
     * @param LoaderInterface|null        $loader The loader for configuring new metadata
     * @param CacheItemPoolInterface|null $cache  The cache for persisting metadata
     *                                            between multiple PHP requests
     */
    public function __construct(LoaderInterface $loader = null, CacheItemPoolInterface $cache = null)
    {
        $this->loader = $loader;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataFor($value, array $context = []): ClassMetadataInterface
    {
        if (!is_object($value) && !is_string($value)) {
            throw new NoSuchMetadataException(sprintf('Cannot create metadata for non-objects. Got: %s', gettype($value)));
        }

        $class = ltrim(is_object($value) ? get_class($value) : $value, '\\');

        if (isset($this->loadedClasses[$class])) {
            return $this->loadedClasses[$class];
        }
        if (null !== $this->cache) {
            $cacheItem = $this->cache->getItem($class);
            if ($this->cache->hasItem($class)) {
                return $this->loadedClasses[$class] = $cacheItem->get();
            }
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
        // Include constraints from all implemented interfaces
        foreach ($parents as $parent) {
            if ($this->hasMetadataFor($parent->name)) {
                $metadata->merge($this->getMetadataFor($parent->name));
            }
        }
        if (null !== $this->loader) {
            $this->loader->loadClassMetadata($metadata);
        }

        if (null !== $this->cache) {
            $cacheItem->set($metadata);
            $this->cache->save($cacheItem);
        }

        return $this->loadedClasses[$class] = $metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function hasMetadataFor($value, array $context = []): bool
    {
        if (!is_object($value) && !is_string($value)) {
            return false;
        }

        $class = ltrim(is_object($value) ? get_class($value) : $value, '\\');

        return $this->loader->hasMetadata($class);
    }
}
