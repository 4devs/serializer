<?php

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
