<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Tests\Visibility;

use FDevs\Serializer\Tests\OptionTest;
use FDevs\Serializer\Visibility\VisibilityInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class VisibilityTest extends OptionTest
{
    /**
     * @return array [propertyName, array options, array context, status]
     */
    abstract public function getData();

    /**
     * @dataProvider getData
     */
    public function testVisibleProperty($propertyName, array $options, array $context, $status)
    {
        $visibility = $this->create();
        $resolver = new OptionsResolver();
        $visibility->configureOptions($resolver);

        $this->assertEquals($visibility->isVisibleProperty($propertyName, $resolver->resolve($options), $context), $status);
    }

    /**
     * test interface.
     */
    public function testInterface()
    {
        $option = $this->create();
        $this->assertInstanceOf(VisibilityInterface::class, $option);
    }
}
