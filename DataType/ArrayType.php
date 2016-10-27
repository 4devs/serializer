<?php

namespace FDevs\Serializer\DataType;

use FDevs\Serializer\OptionRegistry;
use FDevs\Serializer\OptionRegistryAwareInterface;
use FDevs\Serializer\OptionRegistryAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArrayType extends AbstractType implements OptionRegistryAwareInterface
{
    use OptionRegistryAwareTrait;
    /**
     * @var AbstractType
     */
    private $type;

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, array $options, array $context = [])
    {
        $result = [];
        foreach ($data as $key => $item) {
            $result[$key] = $this->getDataType($options['type'])->denormalize($item, $options, $context);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, array $options, array $context = [])
    {
        $result = [];
        foreach ($data as $key => $item) {
            $result[$key] = $this->getDataType($options['type'])->normalize($item, $options, $context);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, array $options)
    {
        return is_array($data);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, array $options)
    {
        return is_array($data);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(['type'])
            ->setDefaults([
                'type' => StringType::class,
            ])
            ->setAllowedTypes('type', [AbstractType::class, 'string']);
    }

    /**
     * @param string $name
     *
     * @return AbstractType
     */
    private function getDataType($name)
    {
        if (!$this->type) {
            $this->type = $this->optionRegistry->getDataType($name);
        }

        return $this->type;
    }
}
