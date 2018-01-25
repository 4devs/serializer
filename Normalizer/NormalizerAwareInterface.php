<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface as BaseNormalizer;

interface NormalizerAwareInterface
{
    /**
     * @param BaseNormalizer $normalizer
     */
    public function setNormalizer(BaseNormalizer $normalizer);
}
