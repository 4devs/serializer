<?php

namespace FDevs\Serializer\Tests\Option;

use FDevs\Serializer\Option\VisibleInterface;

abstract class VisibleTest extends OptionTest
{
    /**
     * @return array
     */
    abstract public function getVisibleData();

    /**
     * @return array
     */
    abstract public function getHiddenData();

    /**
     * test interface.
     */
    public function testInterface()
    {
        $option = $this->init();
        $this->assertInstanceOf(VisibleInterface::class, $option);
    }

    /**
     * @dataProvider getVisibleData
     */
    public function testVisible($property, $options, $context)
    {
        $visibleOption = $this->init();

        $this->assertTrue($visibleOption->isShow($property, $options, $context));
    }

    /**
     * @dataProvider getHiddenData
     */
    public function testHidden($property, $options, $context)
    {
        $visibleOption = $this->init();

        $this->assertFalse($visibleOption->isShow($property, $options, $context));
    }
}
