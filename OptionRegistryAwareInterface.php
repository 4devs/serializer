<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer;

interface OptionRegistryAwareInterface
{
    /**
     * @param OptionRegistryInterface $registry
     */
    public function setOptionRegistry(OptionRegistryInterface $registry);
}
