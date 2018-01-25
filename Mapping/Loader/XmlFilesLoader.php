<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Mapping\Loader;

use FDevs\Serializer\Accessor\GetSet;
use FDevs\Serializer\DataType\StringType;
use FDevs\Serializer\Exception\MappingException;
use FDevs\Serializer\Mapping\ClassMetadata;
use FDevs\Serializer\Mapping\ClassMetadataInterface;
use FDevs\Serializer\Mapping\MetadataInterface;
use FDevs\Serializer\Mapping\PropertyMetadata;
use FDevs\Serializer\OptionRegistry;
use Symfony\Component\Config\Util\XmlUtils;

class XmlFilesLoader extends FilesLoader
{
    /**
     * An array of {@class \SimpleXMLElement} instances.
     *
     * @var \SimpleXMLElement[]|null
     */
    private $classes = [];

    /**
     * {@inheritdoc}
     */
    public function loadClassMetadata(ClassMetadataInterface $classMetadata)
    {
        $name = $classMetadata->getName();

        if (!isset($this->classes[$name])) {
            $this->classes[$name] = $this->parseFile($this->files[$name]);
        }

        $this->loadClassMetadataFromXml($classMetadata, $this->classes[$name]->class[0]);

        return true;
    }

    /**
     * Parses a XML File.
     *
     * @param string $file Path of file
     *
     * @throws MappingException
     *
     * @return \SimpleXMLElement
     */
    private function parseFile($file)
    {
        try {
            $dom = XmlUtils::loadFile($file, __DIR__.'/schema/dic/serializer-mapping/serializer-mapping-1.0.xsd');
        } catch (\Exception $e) {
            throw new MappingException($e->getMessage(), $e->getCode(), $e);
        }

        return simplexml_import_dom($dom);
    }

    /**
     * @param ClassMetadata     $classMetadata
     * @param \SimpleXMLElement $node
     */
    private function loadClassMetadataFromXml(ClassMetadata $classMetadata, \SimpleXMLElement $node)
    {
        $options = $this->parseOptions($node);
        $classMetadata->setOptions($options);

        foreach ($node->property as $node) {
            $propertyName = (string) $node['name'];
            $property = new PropertyMetadata($propertyName);
            $options = $this->parseOptions($node);
            if (count($node->type) > 0) {
                $type = $this->parseMetadataType($node->type[0], OptionRegistry::TYPE_DATA_TYPE);
            } else {
                $type = $this->getMetadataType(StringType::class, OptionRegistry::TYPE_DATA_TYPE);
            }

            if (count($node->accessor) > 0) {
                $this->addPropertyMetadata($property, (string) $node->accessor[0]['name'], $this->parseOptions($node->accessor[0]));
            } else {
                $this->addPropertyMetadata($property, GetSet::class, ['property' => $propertyName]);
            }

            foreach ($node->visible as $item) {
                $this->addPropertyMetadata($property, (string) $item['name'], $this->parseOptions($item));
            }

            foreach ($node->{'name-converter'} as $item) {
                $this->addPropertyMetadata($property, (string) $item['name'], $this->parseOptions($item));
            }

            $property
                ->setType($type)
                ->setOptions($options);

            if (isset($classMetadata[$propertyName])) {
                $classMetadata[$propertyName]->merge($property);
            } else {
                $classMetadata->addPropertyMetadata($property);
            }
        }
    }

    /**
     * @param \SimpleXMLElement $node
     * @param string            $type
     *
     * @return MetadataInterface
     */
    private function parseMetadataType(\SimpleXMLElement $node, $type)
    {
        $options = $this->parseOptions($node);

        return $this->getMetadataType((string) $node['name'], $type, $options);
    }

    /**
     * Parses a collection of "value" XML nodes.
     *
     * @param \SimpleXMLElement $nodes The XML nodes
     *
     * @return array The values
     */
    protected function parseValues(\SimpleXMLElement $node)
    {
        $values = [];

        foreach ($node->value as $item) {
            if ($item->count() > 0) {
                $value = $this->parseValues($item);
            } else {
                $value = trim($item);
            }

            if (isset($item['key'])) {
                $values[(string) $item['key']] = $value;
            } else {
                $values[] = $value;
            }
        }

        return $values;
    }

    /**
     * Parses a collection of "option" XML nodes.
     *
     * @param \SimpleXMLElement $nodes The XML nodes
     *
     * @return array The options
     */
    protected function parseOptions(\SimpleXMLElement $node)
    {
        $options = [];
        /** @var \SimpleXMLElement $option */
        foreach ($node->option as $option) {
            if ($option->count() > 0) {
                if (count($option->value) > 0) {
                    $value = $this->parseValues($option);
                } else {
                    $value = [];
                }
            } else {
                $value = XmlUtils::phpize($option);
                if (is_string($value)) {
                    $value = trim($value);
                }
            }

            $options[(string) $option['name']] = $value;
        }

        return $options;
    }
}
