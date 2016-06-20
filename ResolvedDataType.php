<?php

namespace FDevs\Serializer;

use FDevs\Serializer\DataType\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResolvedDataType
{
    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @var TypeInterface
     */
    private $type;

    /**
     * ResolvedDataType constructor.
     *
     * @param TypeInterface $type
     */
    public function __construct(TypeInterface $type)
    {
        $this->type = $type;
    }

    /**
     * @return TypeInterface
     */
    public function getType()
    {
        $this->type->configureOptions($this->getOptionsResolver());

        return $this->type;
    }

    /**
     * @return OptionsResolver
     */
    public function getOptionsResolver()
    {
        if (!$this->optionsResolver) {
            $this->optionsResolver = new OptionsResolver();
        }

        return $this->optionsResolver;
    }
}
