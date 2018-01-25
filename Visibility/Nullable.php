<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Visibility;

use FDevs\Serializer\Option\AbstractOption;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Nullable extends AbstractOption implements AdvancedVisibilityInterface
{
    /**
     * {@inheritdoc}
     */
    public function isVisibleValue($propertyName, $value, array $options, array $context)
    {
        return is_null($value) ? $options['nullable'] : true;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(['nullable'])
            ->setDefaults(['nullable' => false])
            ->setAllowedTypes('nullable', ['boolean']);
    }
}
