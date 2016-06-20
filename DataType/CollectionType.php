<?php

namespace FDevs\Serializer\DataType;

use FDevs\Serializer\DataTypeFactory;
use FDevs\Serializer\Mapping\MetadataType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionType extends AbstractType
{
    /**
     * @var DataTypeFactory
     */
    protected $dataTypeRegistry;

    /**
     * @var MetadataType
     */
    private $metadataType;

    /**
     * CollectionType constructor.
     *
     * @param DataTypeFactory $dataTypeRegistry
     */
    public function __construct(DataTypeFactory $dataTypeRegistry)
    {
        $this->dataTypeRegistry = $dataTypeRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, array $options, array $context = [])
    {
        $result = [];
        $type = $this->getType($options);
        $optionsType = $this->getTypeOptions($options);
        foreach ($data as $key => $item) {
            $result[$key] = $type->denormalize($item, $optionsType, $context);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, array $options, array $context = [])
    {
        $result = [];
        $type = $this->getType($options);
        $optionsType = $this->getTypeOptions($options);
        foreach ($data as $key => $item) {
            $result[$key] = $type->normalize($item, $optionsType, $context);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['type'])
            ->setDefined(['options'])
            ->setDefaults(['options' => []])
            ->addAllowedTypes('type', ['string'])
            ->addAllowedTypes('options', ['array'])
        ;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    private function getTypeOptions(array $options)
    {
        return $this->dataTypeRegistry->resolveOptions($this->getMetadataType($options));
    }

    /**
     * @param array $options
     *
     * @return TypeInterface
     */
    private function getType(array $options)
    {
        return $this->dataTypeRegistry->getType($this->getMetadataType($options));
    }

    /**
     * @param array $options
     *
     * @return \FDevs\Serializer\Mapping\MetadataType
     */
    private function getMetadataType(array $options)
    {
        if (!$this->metadataType) {
            $this->metadataType = $this->dataTypeRegistry->createType($options['type'], $options['options']);
        }

        return $this->metadataType;
    }
}
