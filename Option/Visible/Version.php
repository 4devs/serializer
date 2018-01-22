<?php

namespace FDevs\Serializer\Option\Visible;

use Composer\Semver\Semver;
use FDevs\Serializer\Option\VisibleInterface;

class Version implements VisibleInterface
{
    const CONTEXT_KEY = 'version';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::CONTEXT_KEY;
    }

    /**
     * {@inheritdoc}
     */
    public function isShow($propertyName, array $options, array $context)
    {
        return !isset($context[static::CONTEXT_KEY]) || Semver::satisfies($context[static::CONTEXT_KEY], reset($options));
    }
}
