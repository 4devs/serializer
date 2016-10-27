<?php

namespace FDevs\Serializer;

trait OptionRegistryAwareTrait
{
    /**
     * @var OptionRegistryInterface
     */
    protected $optionRegistry;

    /**
     * @param OptionRegistryInterface $optionRegistry
     */
    public function setOptionRegistry(OptionRegistryInterface $optionRegistry)
    {
        $this->optionRegistry = $optionRegistry;
    }
}
