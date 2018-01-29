<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Mapping\Guess\Type;

use FDevs\Serializer\Mapping\Guess\Guess;
use FDevs\Serializer\Mapping\Guess\TypeGuesserInterface;
use FDevs\Serializer\Mapping\Guess\TypeGuessInterface;

class ChainTypeGuesser implements TypeGuesserInterface
{
    /**
     * @var iterable|TypeGuesserInterface[]
     */
    private $guesser;

    /**
     * ChainGuesser constructor.
     *
     * @param TypeGuesserInterface[]|iterable $guesser
     */
    public function __construct(iterable $guesser)
    {
        $this->guesser = $guesser;
    }

    /**
     * {@inheritdoc}
     */
    public function guessType(string $class, string $property, array $context = []): ?TypeGuessInterface
    {
        $guesses = [];
        foreach ($this->guesser as $item) {
            $guess = $item->guessType($class, $property, $context);
            if (null !== $guess) {
                $guesses[] = $guess;
            }
        }

        return Guess::getBestGuess($guesses);
    }
}
