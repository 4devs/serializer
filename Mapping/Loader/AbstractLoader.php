<?php

namespace FDevs\Serializer\Mapping\Loader;

use FDevs\Serializer\Mapping\Metadata;
use FDevs\Serializer\Mapping\MetadataInterface;
use FDevs\Serializer\OptionRegistry;

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
     * @param string $name
     * @param string $type
     * @param array  $option
     *
     * @return MetadataInterface
     */
    protected function getMetadataType($name, $type, array $option = [])
    {
        $obj = $this->optionRegistry->getOption($name, $type);
        $resolver = new OptionsResolver();
        $obj->configureOptions($resolver);
        $option = $resolver->resolve($option);

        return new Metadata($name, $option);
    }
}
