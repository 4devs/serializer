<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
