<?php

namespace FDevs\Serializer\DataType;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface TypeInterface
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver);
}
