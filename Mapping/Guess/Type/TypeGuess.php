<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Mapping\Guess\type;

use FDevs\Serializer\Mapping\Guess\Guess;
use FDevs\Serializer\Mapping\Guess\TypeGuessInterface;

class TypeGuess extends Guess implements TypeGuessInterface
{
    /**
     * @var bool
     */
    private $nullable = false;

    /**
     * TypeGuess constructor.
     *
     * @param string $name
     * @param array  $options
     * @param bool   $nullable
     * @param int    $confidence
     */
    public function __construct(string $name, array $options, bool $nullable, int $confidence)
    {
        parent::__construct($name, $options, $confidence);
        $this->nullable = $nullable;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }
}
