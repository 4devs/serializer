<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Exception;

class OptionNotFoundException extends Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct($name, $type, $code = 0, \Exception $previous = null)
    {
        parent::__construct(sprintf('options with name or class "%s" and type "%s" not found', $name, $type), $code, $previous);
    }
}
