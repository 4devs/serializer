<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Mapping\Guess\Accessor;

use FDevs\Serializer\Mapping\Guess\AccessorGuesserInterface;
use FDevs\Serializer\Mapping\Guess\GuessInterface;

class ChainAccessorGuesser implements AccessorGuesserInterface
{
    /**
     * @var iterable|AccessorGuesserInterface[]
     */
    private $accessors;

    /**
     * ChainAccessorGuesser constructor.
     *
     * @param AccessorGuesserInterface[]|iterable $accessors
     */
    public function __construct(iterable $accessors)
    {
        $this->accessors = $accessors;
    }

    /**
     * {@inheritdoc}
     */
    public function guessAccessor(string $class, string $property, array $context = []): ?GuessInterface
    {
        $result = null;
        $maxConfidence = -1;

        foreach ($this->accessors as $accessor) {
            $guess = $accessor->guessAccessor($class, $property, $context);
            if (null !== $guess && $maxConfidence < $confidence = $guess->getConfidence()) {
                $maxConfidence = $confidence;
                $result = $guess;
                if ($confidence >= GuessInterface::VERY_HIGH_CONFIDENCE) {
                    break;
                }
            }
        }

        return $result;
    }
}
