<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Mapping;

class PropertyMetadata extends Metadata implements PropertyMetadataInterface
{
    /**
     * @var MetadataInterface
     */
    private $type;

    /**
     * @var bool
     */
    private $nullable = false;

    /**
     * @var MetadataInterface[]|\iterable
     */
    private $visibility = [];

    /**
     * @var MetadataInterface[]|\iterable
     */
    private $advancedVisibility = [];

    /**
     * @var MetadataInterface[]|\iterable
     */
    private $nameConverter = [];

    /**
     * @var MetadataInterface|\iterable
     */
    private $accessor = [];

    /**
     * @return MetadataInterface
     */
    public function getType(): MetadataInterface
    {
        return $this->type;
    }

    /**
     * @param MetadataInterface $type
     *
     * @return PropertyMetadata
     */
    public function setType(MetadataInterface $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getVisibility(): \iterable
    {
        return $this->visibility;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdvancedVisibility(): \iterable
    {
        return $this->advancedVisibility;
    }

    /**
     * {@inheritdoc}
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @param MetadataInterface $visibility
     *
     * @return $this
     */
    public function addVisibility(MetadataInterface $visibility): self
    {
        $this->visibility[] = $visibility;

        return $this;
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
    public function getNameConverter(): \iterable
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
    public function getAccessor(): MetadataInterface
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
            $this->visibility,
            $this->advancedVisibility,
            $this->nullable,
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
            $this->visibility,
            $this->advancedVisibility,
            $this->nullable,
            $this->nameConverter,
            $parentStr) = unserialize($str);
        parent::unserialize($parentStr);
    }
}
