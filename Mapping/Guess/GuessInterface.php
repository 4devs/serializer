<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Mapping\Guess;

interface GuessInterface
{
    /**
     * Marks an instance with a value that is extremely likely to be correct.
     */
    public const VERY_HIGH_CONFIDENCE = 30;

    /**
     * Marks an instance with a value that is very likely to be correct.
     */
    public const HIGH_CONFIDENCE = 20;

    /**
     * Marks an instance with a value that is likely to be correct.
     */
    public const MEDIUM_CONFIDENCE = 10;

    /**
     * Marks an instance with a value that may be correct.
     */
    public const LOW_CONFIDENCE = 0;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return array
     */
    public function getOptions(): array;

    /**
     * Returns the confidence that the guessed value is correct.
     *
     * @return int One of the constants VERY_HIGH_CONFIDENCE, HIGH_CONFIDENCE,
     *             MEDIUM_CONFIDENCE and LOW_CONFIDENCE
     */
    public function getConfidence(): int;
}
