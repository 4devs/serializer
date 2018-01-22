<?php

namespace FDevs\Serializer;

use FDevs\Serializer\Mapping\PropertyMetadataInterface;

class Property implements PropertyInterface
{
    /**
     * @var PropertyMetadataInterface
     */
    protected $meta;

    /**
     * @var OptionManagerInterface
     */
    protected $optionManager;

    /**
     * @var array
     */
    protected $context = [];

    /**
     * @var string
     */
    private $name;

    /**
     * Property constructor.
     *
     * @param PropertyMetadataInterface $meta
     * @param array                     $context
     * @param OptionManagerInterface    $optionManager
     */
    public function __construct(PropertyMetadataInterface $meta, array $context, OptionManagerInterface $optionManager)
    {
        $this->meta = $meta;
        $this->optionManager = $optionManager;
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        if (!$this->name) {
            $this->name = $this->meta->getName();
            foreach ($this->meta->getNameConverter() as $metadata) {
                $this->name = $this->optionManager->convertName($metadata, $this->name, $this->context);
            }
        }

        return $this->name;
    }
}
