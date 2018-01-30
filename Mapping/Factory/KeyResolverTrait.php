<?php

namespace FDevs\Serializer\Mapping\Factory;

trait KeyResolverTrait
{
    /**
     * @param object|string $value
     * @param array $context
     * @return string
     */
    private function getKeyPrefix($value, array $context): string
    {
        return 'FDevs_' . md5(serialize([$value, $context]));
    }
}
