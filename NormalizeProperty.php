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
    public function isVisible($value)
    {
        foreach ($this->meta->getVisible() as $metadata) {
            if (!$this->optionManager->isVisible($metadata, $this->getName(), $value, $this->context)) {
                return false;
            }
        }

        return true;
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
        return $this->optionManager->normalize($this->meta->getType(), $value, $this->context, $this->normalizer);
    }
}
