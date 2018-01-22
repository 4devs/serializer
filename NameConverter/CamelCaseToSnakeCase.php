<?php

namespace FDevs\Serializer\NameConverter;

use FDevs\Serializer\Option\AbstractOption;
use FDevs\Serializer\Option\NameConverterInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class CamelCaseToSnakeCase extends AbstractOption implements NameConverterInterface
{
    /**
     * @var CamelCaseToSnakeCaseNameConverter
     */
    private $converter;

    /**
     * CamelCaseToSnakeCase constructor.
     *
     * @param CamelCaseToSnakeCaseNameConverter $converter
     */
    public function __construct(CamelCaseToSnakeCaseNameConverter $converter = null)
    {
        $this->converter = $converter ?: new CamelCaseToSnakeCaseNameConverter();
    }

    /**
     * {@inheritdoc}
     */
    public function convert($propertyName, array $options, array $context = [])
    {
        return $this->converter->normalize($propertyName);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'camel-to-snake';
    }
}
