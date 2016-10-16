<?php

namespace FDevs\Serializer;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface OptionInterface
{
    /**
     * unique name option.
     *
     * @return string
     */
    public function getName();

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver);
}
