<?php

namespace FDevs\Serializer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class NormalizeProperty extends Property implements NormalizePropertyInterface
{
    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * @param NormalizerInterface|null $normalizer
     *
     * @return NormalizeProperty
     */
    public function setNormalizer($normalizer)
    {
        $this->normalizer = $normalizer;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isVisible()
    {
        $visible = true;
        foreach ($this->meta->getVisibility() as $metadata) {
            $visible = $this->optionManager->isVisibleProperty($metadata, $this->getName(), $this->context);
            if (true === $visible) {
                break;
            }
        }

        return $visible;
    }

    /**
     * {@inheritdoc}
     */
    public function isVisibleValue($value)
    {
        $visible = true;
        foreach ($this->meta->getAdvancedVisibility() as $metadata) {
            $visible = $this->optionManager->isVisibleValue($metadata, $this->getName(), $value, $this->context);
            if (true === $visible) {
                break;
            }
        }

        return $visible;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue($object)
    {
        return $this->optionManager->getValue($this->meta->getAccessor(), $object, $this->context);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($value)
    {
        return $this->meta->isNullable() && null === $value ? $value : $this->optionManager->normalize($this->meta->getType(), $value, $this->context, $this->normalizer);
    }
}
