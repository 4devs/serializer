<?php

namespace FDevs\Serializer\Accessor;

use FDevs\Serializer\Option\AccessorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyAccess\PropertyPathInterface;

class Property implements AccessorInterface
{
    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * PropertyAccessor constructor.
     *
     * @param PropertyAccessorInterface|null $propertyAccessor
     */
    public function __construct(PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function getValue($object, array $options = [], array $context = [])
    {
        return $this->propertyAccessor->getValue($object, $options['property']);
    }

    /**
     * {@inheritdoc}
     */
    public function setValue(&$object, $value, array $options = [], array $context = [])
    {
        $this->propertyAccessor->setValue($object, $options['property'], $value);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['property'])
            ->addAllowedTypes('property', ['string', PropertyPathInterface::class]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'property';
    }
}
