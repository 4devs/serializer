<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Mapping\NameConverter;

use FDevs\Serializer\Mapping\NameConverterInterface;

class ChainConverter implements NameConverterInterface
{
    /**
     * @var iterable|NameConverterInterface[]
     */
    private $converters;

    /**
     * ChainConverter constructor.
     *
     * @param NameConverterInterface[]|iterable $converters
     */
    public function __construct(iterable $converters)
    {
        $this->converters = $converters;
    }

    /**
     * {@inheritdoc}
     */
    public function convert($value, string $propertyName, array $context = []): string
    {
        foreach ($this->converters as $converter) {
            $propertyName = $converter->convert($value, $propertyName, $context);
        }

        return $propertyName;
    }
}
