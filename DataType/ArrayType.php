<?php

namespace FDevs\Serializer\DataType;

use FDevs\Serializer\OptionInterface;
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
     * @var OptionsResolver[]
     */
    private $resolver;

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, array $options, array $context = [])
    {
        $result = [];
        $type = $this->getDataType($options['type']);
        $optionType = $this->resolve($type, $options['options_type']);
        foreach ($data as $key => $item) {
            $result[$key] = $type->denormalize($item, $optionType, $context);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, array $options, array $context = [])
    {
        $result = [];
        $type = $this->getDataType($options['type']);
        $optionType = $this->resolve($type, $options['options_type']);
        foreach ($data as $key => $item) {
            $result[$key] = $type->normalize($item, $optionType, $context);
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
            ->setDefaults([
                'type' => StringType::class,
                'options_type' => [],
            ])
            ->setAllowedTypes('type', [AbstractType::class, 'string'])
            ->setAllowedTypes('options_type', ['array'])
        ;
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

    /**
     * @param OptionInterface $type
     * @param array        $options
     *
     * @return array
     */
    private function resolve(OptionInterface $type, array $options)
    {
        $oid = spl_object_hash($type);
        if (!isset($this->resolver[$oid])) {
            $this->resolver[$oid] = new OptionsResolver();
            $type->configureOptions($this->resolver[$oid]);
        }

        return $this->resolver[$oid]->resolve($options);
    }
}
