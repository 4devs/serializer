<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class DenormalizeProperty extends Property implements DenormalizePropertyInterface
{
    /**
     * @var DenormalizerInterface|null
     */
    private $denormalizer;

    /**
     * @param null|DenormalizerInterface $denormalizer
     *
     * @return DenormalizeProperty
     */
    public function setDenormalizer($denormalizer)
    {
        $this->denormalizer = $denormalizer;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isVisible()
    {
        foreach ($this->meta->getVisible() as $metadata) {
            if (!$this->optionManager->isVisible($metadata, $this->getName(), $this->context)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($object, $data)
    {
        $this->optionManager->setValue($this->meta->getAccessor(), $object, $data, $this->context);
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($value)
    {
        return $this->optionManager->denormalize($this->meta->getType(), $value, $this->context, $this->denormalizer);
    }
}
