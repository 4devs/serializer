<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Exception;

class NoSuchAccessorException extends NoSuchMetadataException
{
    /**
     * @var string
     */
    private $className;
    /**
     * @var string
     */
    private $propertyName;
    /**
     * @var array
     */
    private $context;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $className, string $propertyName, array $context, \Throwable $previous = null)
    {
        $message = sprintf('Accessor for the class name "%s" and property name "%s" and context "%s" not found', $className, $propertyName);
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
