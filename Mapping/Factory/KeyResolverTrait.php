<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Mapping\Factory;

trait KeyResolverTrait
{
    /**
     * @param object|string $value
     * @param array         $context
     *
     * @return string
     */
    private function getKeyPrefix($value, array $context): string
    {
        return 'FDevs_'.md5(serialize([$value, $context]));
    }
}
