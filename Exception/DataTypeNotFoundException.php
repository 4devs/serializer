<?php

namespace FDevs\Serializer\Exception;

class DataTypeNotFoundException extends Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct($type, $class, $code = 0, \Exception $previous = null)
    {
        parent::__construct(sprintf('type "%s" or class "%s" not found', $type, $class), $code, $previous);
    }
}
