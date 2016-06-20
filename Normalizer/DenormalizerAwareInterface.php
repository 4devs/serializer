<?php

namespace FDevs\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface as BaseDenormalizer;

interface DenormalizerAwareInterface
{
    /**
     * @param BaseDenormalizer $denormalizer
     */
    public function setDenormalizer(BaseDenormalizer $denormalizer);
}
