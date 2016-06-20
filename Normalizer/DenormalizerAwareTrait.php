<?php

namespace FDevs\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface as BaseDenormalizer;

trait DenormalizerAwareTrait
{
    /**
     * @var BaseDenormalizer
     */
    private $denormalizer;

    /**
     * @return BaseDenormalizer
     */
    protected function getDenormalizer()
    {
        return $this->denormalizer;
    }

    /**
     * @param BaseDenormalizer $denormalizer
     *
     * @return self
     */
    public function setDenormalizer(BaseDenormalizer $denormalizer)
    {
        $this->denormalizer = $denormalizer;

        return $this;
    }
}
