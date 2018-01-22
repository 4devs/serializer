<?php

namespace FDevs\Serializer\Tests\Option;

use FDevs\Serializer\Option\NameConverterInterface;
use FDevs\Serializer\Option\OptionInterface;
use FDevs\Serializer\Option\VisibleInterface;
use PHPUnit\Framework\TestCase;

abstract class OptionTest extends TestCase
{
    /**
     * @return OptionInterface|VisibleInterface|NameConverterInterface
     */
    abstract public function init();

    /**
     * test interface.
     */
    public function testInterface()
    {
        $option = $this->init();
        $this->assertInstanceOf(OptionInterface::class, $option);
    }

    /**
     * test name.
     */
    public function testName()
    {
        $option = $this->init();
        $this->assertInternalType('string', $option->getName());
    }
}
