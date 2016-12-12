<?php

namespace FDevs\Serializer\Visibility;

use FDevs\Serializer\Option\AbstractOption;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Group extends AbstractOption implements VisibilityInterface
{
    /**
     * {@inheritdoc}
     */
    public function isVisibleProperty($propertyName, array $options, array $context)
    {
        $show = false;
        if (!$options['required'] && !isset($context[$options['key']])) {
            $show = true;
        } elseif (isset($context[$options['key']])) {
            foreach ($options as $option) {
                if (in_array($option, $context[$options['key']])) {
                    $show = true;
                    break;
                }
            }
        }

        return $show;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('groups')
            ->setAllowedTypes('groups', ['array'])
            ->setDefined(['key', 'required'])
            ->setDefaults([
                'key' => 'groups',
                'required' => true,
            ])
            ->setAllowedTypes('key', ['string'])
            ->setAllowedTypes('required', ['boolean']);
    }
}
