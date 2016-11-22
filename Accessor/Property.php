<?php

namespace FDevs\Serializer\Accessor;

use FDevs\Serializer\Option\AccessorInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyAccess\PropertyPathInterface;
use Symfony\Component\PropertyAccess\Exception\ExceptionInterface;

class Property implements AccessorInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
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
        $value = null;
        try {
            $value = $this->propertyAccessor->getValue($object, $options['property']);
        } catch (ExceptionInterface $e) {
            if ($this->logger) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
            }
            if ($options['strict']) {
                throw $e;
            }
        }

        return $value;
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
            ->setDefined(['strict'])
            ->setDefaults(['strict' => true])
            ->addAllowedTypes('property', ['string', PropertyPathInterface::class])
            ->addAllowedTypes('strict', ['boolean']);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'property';
    }
}
