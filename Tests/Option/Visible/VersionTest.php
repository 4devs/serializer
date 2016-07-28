<?php

namespace FDevs\Serializer\Tests\Option\Visible;

use FDevs\Serializer\Option\Visible\Version;
use FDevs\Serializer\Tests\Option\VisibleTest;

class VersionTest extends VisibleTest
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        return new Version();
    }

    /**
     * {@inheritdoc}
     */
    public function getVisibleData()
    {
        return [
            ['property', ['<1.4 >1.0-dev'], [Version::CONTEXT_KEY => '1.2.0']],
            ['property', ['<1.4 >1.0-dev'], [Version::CONTEXT_KEY => '1.2']],
            ['property', ['~1.4 || ~1.0'], [Version::CONTEXT_KEY => '1.4.1']],
            ['property', ['~1.4 || ~1.0'], [Version::CONTEXT_KEY => '1.0-dev']],
            ['property', ['~1.4 || ^3.0'], [Version::CONTEXT_KEY => '1.4']],
            ['property', ['~1.4 || ^3.0'], [Version::CONTEXT_KEY => '3.4']],
            ['property', ['^3.4'], [Version::CONTEXT_KEY => '3.5']],
            ['property', ['^3.4'], [Version::CONTEXT_KEY => '3.4.0']],
            ['property', ['3.4-dev'], [Version::CONTEXT_KEY => '3.4-dev']],
            ['property', ['<1.4 >1.0-dev'], []],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getHiddenData()
    {
        return [
            ['property', ['<1.4 >1.0-dev'], [Version::CONTEXT_KEY => '1.5']],
            ['property', ['~3.4 || ~1.0'], [Version::CONTEXT_KEY => '2.1']],
            ['property', ['3.4-dev'], [Version::CONTEXT_KEY => '3.4.0']],
            ['property', ['^3.4'], [Version::CONTEXT_KEY => '2.1']],
        ];
    }
}
