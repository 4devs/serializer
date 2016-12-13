<?php

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
