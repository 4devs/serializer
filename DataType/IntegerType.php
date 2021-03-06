<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\DataType;

class IntegerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, array $options, array $context = [])
    {
        return intval($data);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, array $options, array $context = [])
    {
        return intval($data);
    }
}
