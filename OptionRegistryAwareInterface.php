<?php

namespace FDevs\Serializer;

interface OptionRegistryAwareInterface
{
    /**
     * @param OptionRegistryInterface $registry
     */
    public function setOptionRegistry(OptionRegistryInterface $registry);
}
