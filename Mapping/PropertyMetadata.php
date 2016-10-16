<?php

namespace FDevs\Serializer\Mapping;

class PropertyMetadata extends Metadata implements PropertyMetadataInterface
{
    /**
     * @var MetadataInterface
     */
    protected $type;

    /**
     * @var MetadataInterface[]
     */
    protected $visible = [];

    /**
     * @var MetadataInterface[]
     */
    protected $nameConverter = [];

    /**
     * @var MetadataInterface
     */
    protected $accessor = [];

    /**
     * @return MetadataInterface
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param MetadataInterface $type
     *
     * @return PropertyMetadata
     */
    public function setType(MetadataInterface $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param MetadataInterface $visible
     *
     * @return $this
     */
    public function addVisible(MetadataInterface $visible)
    {
        $this->visible[] = $visible;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getNameConverter()
    {
        return $this->nameConverter;
    }

    /**
     * @param MetadataInterface $nameConverter
     *
     * @return $this
     */
    public function addNameConverter(MetadataInterface $nameConverter)
    {
        $this->nameConverter[] = $nameConverter;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessor()
    {
        return $this->accessor;
    }

    /**
     * @param MetadataInterface $accessor
     *
     * @return PropertyMetadata
     */
    public function setAccessor(MetadataInterface $accessor)
    {
        $this->accessor = $accessor;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->type,
            $this->accessor,
            $this->visible,
            $this->nameConverter,
            parent::serialize(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($str)
    {
        list(
            $this->type,
            $this->accessor,
            $this->visible,
            $this->nameConverter,
            $parentStr) = unserialize($str);
        parent::unserialize($parentStr);
    }
}
