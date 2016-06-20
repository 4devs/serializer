<?php

namespace FDevs\Serializer\DataType;

class BooleanType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, array $options, array $context = [])
    {
        return $data === 'false' ? false : boolval($data);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, array $options, array $context = [])
    {
        return $this->denormalize($data, $options, $context);
    }
}
