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
    protected $visibility = [];

    /**
     * @var MetadataInterface[]
     */
    protected $advancedVisibility = [];

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
        return $this->advancedVisibility;
    }

    /**
     * @param MetadataInterface $visible
     *
     * @return $this
     *
     * @deprecated
     */
    public function addVisible(MetadataInterface $visible)
    {
        $this->advancedVisibility[] = $visible;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdvancedVisibility()
    {
        return $this->advancedVisibility;
    }

    /**
     * @param MetadataInterface $visibility
     */
    public function addVisibility(MetadataInterface $visibility)
    {
        $this->visibility[] = $visibility;
    }

    /**
     * @param MetadataInterface $advancedVisibility
     */
    public function addAdvancedVisibility(MetadataInterface $advancedVisibility)
    {
        $this->advancedVisibility[] = $advancedVisibility;
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
