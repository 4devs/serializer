<?php

namespace FDevs\Serializer\Mapping\Loader;

use FDevs\Serializer\DataTypeFactory;
use FDevs\Serializer\Mapping\MetadataType;

abstract class AbstractLoader implements LoaderInterface
{
    /**
     * @var DataTypeFactory
     */
    private $dataTypeFactory;

    /**
     * @param DataTypeFactory $dataTypeFactory
     */
    public function setDataTypeFactory(DataTypeFactory $dataTypeFactory)
    {
        $this->dataTypeFactory = $dataTypeFactory;
    }

    /**
     * @return DataTypeFactory
     */
    protected function getDataTypeFactory()
    {
        return $this->dataTypeFactory ?: $this->dataTypeFactory = new DataTypeFactory();
    }

    /**
     * Creates a type instance for the given type name.
     *
     * @param string $name
     * @param mixed  $options The type options
     *
     * @return MetadataType
     */
    protected function newType($name, array $options = [])
    {
        return $this->getDataTypeFactory()->createType($name, $options);
    }
}
