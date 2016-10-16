<?php

namespace FDevs\Serializer\Mapping\Factory;

use Psr\Cache\CacheItemPoolInterface;

class CacheMetadataFactory implements MetadataFactoryInterface
{
    use ClassResolverTrait;

    /**
     * @var MetadataFactoryInterface
     */
    private $factory;

    /**
     * @var CacheItemPoolInterface
     */
    private $cacheItemPool;

    /**
     * CacheMetadataFactory constructor.
     *
     * @param MetadataFactoryInterface $decorated
     * @param CacheItemPoolInterface   $cacheItemPool
     */
    public function __construct(MetadataFactoryInterface $decorated, CacheItemPoolInterface $cacheItemPool)
    {
        $this->factory = $decorated;
        $this->cacheItemPool = $cacheItemPool;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataFor($value)
    {
        $class = $this->getClass($value);
        // Key cannot contain backslashes according to PSR-6
        $key = strtr($class, '\\', '_');

        $item = $this->cacheItemPool->getItem($key);
        if ($item->isHit()) {
            return $item->get();
        }

        $metadata = $this->factory->getMetadataFor($value);
        $this->cacheItemPool->save($item->set($metadata));

        return $metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function hasMetadataFor($value)
    {
        return $this->factory->hasMetadataFor($value);
    }
}
