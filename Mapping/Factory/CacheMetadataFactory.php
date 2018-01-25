<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Mapping\Factory;

use FDevs\Serializer\Mapping\ClassMetadataInterface;
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
    public function getMetadataFor($value, array $context = []): ClassMetadataInterface
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
    public function hasMetadataFor($value, array $context = []): bool
    {
        return $this->factory->hasMetadataFor($value);
    }
}
