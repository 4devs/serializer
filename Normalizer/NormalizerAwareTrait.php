<?php

namespace FDevs\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface as BaseNormalizer;

trait NormalizerAwareTrait
{
    /**
     * @var BaseNormalizer
     */
    private $normalizer;

    /**
     * @return BaseNormalizer
     */
    protected function getNormalizer()
    {
        return $this->normalizer;
    }

    /**
     * @param BaseNormalizer $normalizer
     *
     * @return self
     */
    public function setNormalizer(BaseNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;

        return $this;
    }
}
