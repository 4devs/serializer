<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Tests;

use FDevs\Serializer\Option\NameConverterInterface;
use FDevs\Serializer\OptionInterface;
use FDevs\Serializer\Visibility\VisibilityInterface;

abstract class OptionTest extends TestCase
{
    /**
     * @return OptionInterface|VisibilityInterface|NameConverterInterface
     */
    abstract public function create();

    /**
     * test interface.
     */
    public function testInterface()
    {
        $option = $this->create();
        $this->assertInstanceOf(OptionInterface::class, $option);
    }

    /**
     * test name.
     */
    public function testName()
    {
        $option = $this->create();
        $this->assertInternalType('string', $option->getName());
    }
}
