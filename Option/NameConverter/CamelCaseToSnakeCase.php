<?php

namespace FDevs\Serializer\Option\NameConverter;

use FDevs\Serializer\Option\NameConverterInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class CamelCaseToSnakeCase implements NameConverterInterface
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
    public function convert($propertyName, array $options, $type = self::TYPE_NORMALIZE)
    {
        return $this->converter->normalize($propertyName);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'camel_to_snake';
    }
}
