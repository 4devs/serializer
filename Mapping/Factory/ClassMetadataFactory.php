<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Mapping\Factory;

use FDevs\Serializer\Mapping\ClassMetadata;
use FDevs\Serializer\Mapping\ClassMetadataInterface;
use Symfony\Component\PropertyInfo\PropertyListExtractorInterface;

class ClassMetadataFactory implements MetadataFactoryInterface
{
    use ClassResolverTrait;
    use KeyResolverTrait;
    /**
     * @var PropertyListExtractorInterface
     */
    private $propertyListExtractor;

    /**
     * @var PropertyMetadataFactoryInterface
     */
    private $propertyMetadataFactory;

    /**
     * @var ClassMetadataInterface[]
     */
    private $metas = [];

    /**
     * {@inheritdoc}
     */
    public function getMetadataFor($value, array $context = []): ClassMetadataInterface
    {
        $key = $this->getKeyPrefix($value, $context);
        if (empty($this->metas[$key])) {
            $class = $this->getClass($value);
            $meta = new ClassMetadata($class);
            $properties = $this->getProperties($value, $context);
            foreach ($properties as $property) {
                if ($this->propertyMetadataFactory->hasMetadataFor($value, $property, $context)) {
                    $meta->addPropertyMetadata($this->propertyMetadataFactory->getMetadataFor($value, $property, $context));
                }
            }
            $this->metas[$key] = $meta;
        }

        return $this->metas[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function hasMetadataFor($value, array $context = []): bool
    {
        return (is_object($value) || is_string($value)) && null !== $this->getProperties($value, $context);
    }

    private function getProperties($value, array $context = []): ?array
    {
        return $this->propertyListExtractor->getProperties($this->getClass($value), $context);
    }
}
