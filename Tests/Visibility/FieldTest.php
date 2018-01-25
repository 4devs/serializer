<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Tests\Visibility;

use FDevs\Serializer\Visibility\Field;

class FieldTest extends VisibilityTest
{
    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return [
            ['field', [], [], false],
            ['field', ['required' => false], [], true],
            ['field', ['required' => false], ['fields' => ['field']], true],
            ['field', ['required' => false], ['fields' => ['other_field']], false],
            ['field', ['required' => false], ['fields' => []], false],
            ['field', ['required' => true], ['fields' => []], false],
            ['field', ['key' => 'key_field'], ['key_field' => []], false],
            ['field', ['key' => 'key_field'], ['field' => []], false],
            ['name', [], ['fields' => ['name', 'name2']], true],
            ['name', [], ['fields' => 'text'], false],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        return new Field();
    }
}
