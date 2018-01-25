<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Exception;

class UnsupportedDataTypeException extends Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct($valueType, $dataType, $code = 0, \Exception $previous = null)
    {
        parent::__construct(sprintf('Unsupported value "%s" by type data "%s"', $valueType, $dataType), $code, $previous);
    }
}
