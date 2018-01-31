<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Mapping\Guess;

class Guess implements GuessInterface
{
    /**
     * The confidence about the correctness of the value.
     *
     * One of VERY_HIGH_CONFIDENCE, HIGH_CONFIDENCE, MEDIUM_CONFIDENCE
     * and LOW_CONFIDENCE.
     *
     * @var int
     */
    private $confidence = self::LOW_CONFIDENCE;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $options;

    /**
     * @param string $name
     * @param array  $options
     * @param int    $confidence The confidence
     */
    public function __construct(string $name, array $options, int $confidence)
    {
        $this->confidence = $confidence;
        $this->options = $options;
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Returns the confidence that the guessed value is correct.
     *
     * @return int One of the constants VERY_HIGH_CONFIDENCE, HIGH_CONFIDENCE,
     *             MEDIUM_CONFIDENCE and LOW_CONFIDENCE
     */
    public function getConfidence(): int
    {
        return $this->confidence;
    }

    /**
     * Returns the guess most likely to be correct from a list of guesses.
     *
     * If there are multiple guesses with the same, highest confidence, the
     * returned guess is any of them.
     *
     * @param GuessInterface[]|TypeGuessInterface[] $guesses An array of guesses
     *
     * @return GuessInterface|TypeGuessInterface|null
     */
    public static function getBestGuess(array $guesses)
    {
        $result = null;
        $maxConfidence = -1;

        foreach ($guesses as $guess) {
            if ($maxConfidence < $confidence = $guess->getConfidence()) {
                $maxConfidence = $confidence;
                $result = $guess;
            }
        }

        return $result;
    }
}
