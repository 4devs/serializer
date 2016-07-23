<?php

namespace FDevs\Serializer\DataType;

class FloatType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, array $options, array $context = [])
    {
        return floatval($data);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, array $options, array $context = [])
    {
        return floatval($data);
    }
}
