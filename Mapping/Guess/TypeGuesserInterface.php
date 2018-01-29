<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Mapping\Guess;

interface TypeGuesserInterface
{
    /**
     * Returns a field guess for a property name of a class.
     *
     * @param string $class    The fully qualified class name
     * @param string $property The name of the property to guess for
     * @param array  $context
     *
     * @return TypeGuessInterface|null A guess for the field's type and options
     */
    public function guessType(string $class, string $property, array $context = []): ?TypeGuessInterface;
}
