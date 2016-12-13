<?php

namespace FDevs\Serializer\Tests\Visibility;

use FDevs\Serializer\Visibility\Version;

class VersionTest extends VisibilityTest
{
    /**
     * {@inheritdoc}
     */
    public function create()
    {
        return new Version();
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return [
            ['property', ['version' => '<1.4 >1.0-dev', 'key' => 'key_version'], ['key_version' => '1.2.0'], true],
            ['property', ['version' => '<1.4 >1.0-dev'], ['version' => '1.2'], true],
            ['property', ['version' => '~1.4 || ~1.0'], ['version' => '1.4.1'], true],
            ['property', ['version' => '~1.4 || ~1.0'], ['version' => '1.0-dev'], true],
            ['property', ['version' => '~1.4 || ^3.0'], ['version' => '1.4'], true],
            ['property', ['version' => '~1.4 || ^3.0'], ['version' => '3.4'], true],
            ['property', ['version' => '^3.4'], ['version' => '3.5'], true],
            ['property', ['version' => '^3.4'], ['version' => '3.4.0'], true],
            ['property', ['version' => '3.4-dev'], ['version' => '3.4-dev'], true],
            ['property', ['version' => '<1.4 >1.0-dev'], [], true],
            ['property', ['version' => '<1.4 >1.0-dev'], ['version' => '1.5'], false],
            ['property', ['version' => '~3.4 || ~1.0'], ['version' => '2.1'], false],
            ['property', ['version' => '3.4-dev'], ['version' => '3.4.0'], false],
            ['property', ['version' => '^3.4'], ['version' => '2.1'], false],
        ];
    }
}
