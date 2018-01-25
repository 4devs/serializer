<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
