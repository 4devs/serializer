<?php

namespace FDevs\Serializer\Mapping\Loader;

use FDevs\Serializer\Mapping\Metadata;
use FDevs\Serializer\Mapping\MetadataInterface;
use FDevs\Serializer\Mapping\PropertyMetadata;
use FDevs\Serializer\Option\AccessorInterface;
use FDevs\Serializer\Option\NameConverterInterface;
use FDevs\Serializer\OptionInterface;
use FDevs\Serializer\OptionRegistry;
use FDevs\Serializer\Visibility\AdvancedVisibilityInterface;
use FDevs\Serializer\Visibility\VisibilityInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractLoader implements LoaderInterface
{
    /**
     * @var OptionRegistry
     */
    private $optionRegistry;

    /**
     * AbstractLoader constructor.
     *
     * @param OptionRegistry $optionRegistry
     */
    public function __construct(OptionRegistry $optionRegistry)
    {
        $this->optionRegistry = $optionRegistry;
    }

    /**
     * @param PropertyMetadata $metadata
     * @param string           $name
     * @param array            $option
     *
     * @return $this
     */
    protected function addPropertyMetadata(PropertyMetadata $metadata, $name, array $option = [])
    {
        $obj = $this->getOption($name);
        $meta = new Metadata($name, $this->resolveOptions($obj, $option));
        if ($obj instanceof AdvancedVisibilityInterface) {
            $metadata->addAdvancedVisibility($meta);
        }
        if ($obj instanceof VisibilityInterface) {
            $metadata->addVisibility($meta);
        }
        if ($obj instanceof AccessorInterface) {
            $metadata->setAccessor($meta);
        }
        if ($obj instanceof NameConverterInterface) {
            $metadata->addNameConverter($meta);
        }

        return $this;
    }

    /**
     * @param string      $name
     * @param null|string $type
     *
     * @return OptionInterface
     */
    protected function getOption($name, $type = null)
    {
        return $this->optionRegistry->getOption($name, $type);
    }

    /**
     * @param OptionInterface $obj
     * @param array           $options
     *
     * @return array
     */
    protected function resolveOptions(OptionInterface $obj, array $options = [])
    {
        $resolver = new OptionsResolver();
        $obj->configureOptions($resolver);

        return $resolver->resolve($options);
    }

    /**
     * @param string $name
     * @param string $type
     * @param array  $option
     *
     * @return MetadataInterface
     */
    protected function getMetadataType($name, $type, array $option = [])
    {
        $obj = $this->getOption($name, $type);

        return new Metadata($name, $this->resolveOptions($obj, $option));
    }
}
