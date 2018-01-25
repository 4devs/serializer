<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Visibility;

use Composer\Semver\Semver;
use FDevs\Serializer\Option\AbstractOption;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Version extends AbstractOption implements VisibilityInterface
{
    /**
     * {@inheritdoc}
     */
    public function isVisibleProperty($propertyName, array $options, array $context)
    {
        return !isset($context[$options['key']]) || Semver::satisfies($context[$options['key']], $options['version']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['version'])
            ->setDefined(['key'])
            ->setDefault('key', 'version')
            ->setAllowedTypes('key', ['string'])
            ->setAllowedTypes('version', ['string']);
    }
}
