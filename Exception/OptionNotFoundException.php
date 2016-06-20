<?php

namespace FDevs\Serializer\Exception;

class OptionNotFoundException extends Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct($name, $code = 0, \Exception $previous = null)
    {
        parent::__construct(sprintf('options with name or class "%s" not found', $name), $code, $previous);
    }
}
