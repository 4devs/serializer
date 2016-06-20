<?php

namespace FDevs\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface as BaseNormalizer;

interface NormalizerAwareInterface
{
    /**
     * @param BaseNormalizer $normalizer
     */
    public function setNormalizer(BaseNormalizer $normalizer);
}
