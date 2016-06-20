<?php

namespace FDevs\Serializer\DataType;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class DoctrineType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * DoctrineType constructor.
     *
     * @param ObjectManager             $objectManager
     * @param PropertyAccessorInterface $propertyAccessor
     */
    public function __construct(ObjectManager $objectManager, PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->objectManager = $objectManager;
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, array $options, array $context = [])
    {
        return $this->objectManager->find($options['class'], $data);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, array $options, array $context = [])
    {
        return $this->propertyAccessor->getValue($data, $options['property']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('class')
            ->setDefined(['property'])
            ->setDefaults(['property' => 'id'])
            ->addAllowedTypes('class', ['string'])
            ->addAllowedTypes('property', ['string'])
        ;
    }
}
