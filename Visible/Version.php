<?php

namespace FDevs\Serializer\Visible;

use FDevs\Serializer\Option\VisibleInterface;
use Composer\Semver\Semver;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Version implements VisibleInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'version';
    }

    /**
     * {@inheritdoc}
     */
    public function isVisible($propertyName, $value, array $options, array $context)
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
