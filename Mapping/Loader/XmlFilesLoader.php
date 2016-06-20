<?php

namespace FDevs\Serializer\Mapping\Loader;

use FDevs\Serializer\Exception\MappingException;
use FDevs\Serializer\Mapping\ClassMetadata;
use FDevs\Serializer\Mapping\MetadataType;
use FDevs\Serializer\Mapping\PropertyMetadata;
use Symfony\Component\Config\Util\XmlUtils;
use FDevs\Serializer\Mapping\ClassMetadataInterface;

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
     * @return \SimpleXMLElement
     *
     * @throws MappingException
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
        $options = [];
        if (count($node->option) > 0) {
            $options = $this->parseOptions($node->option);
        }
        $classMetadata->setOptions($options);

        foreach ($node->property as $node) {
            $propertyName = (string) $node['name'];
            $property = new PropertyMetadata((string) $node['name']);
            $options = [];
            if (count($node->type) > 0) {
                $type = $this->parseType($node->type[0]);
            } else {
                $type = $this->newType('string');
            }
            if (count($node->option) > 0) {
                $options = $this->parseOptions($node->option);
            }
            $property
                ->setType($type)
                ->setOptions($options)
            ;

            if (isset($classMetadata[$propertyName])) {
                $classMetadata[$propertyName]->merge($property);
            } else {
                $classMetadata->addPropertyMetadata($property);
            }
        }
    }

    /**
     * @param \SimpleXMLElement $node
     *
     * @return MetadataType
     */
    private function parseType(\SimpleXMLElement $node)
    {
        $options = [];
        if (count($node->option) > 0) {
            $options = $this->parseOptions($node->option);
        }

        return $this->newType((string) $node['name'], $options);
    }

    /**
     * Parses a collection of "value" XML nodes.
     *
     * @param \SimpleXMLElement $nodes The XML nodes
     *
     * @return array The values
     */
    protected function parseValues(\SimpleXMLElement $nodes)
    {
        $values = [];

        foreach ($nodes as $node) {
            if (count($node) > 0) {
                if (count($node->value) > 0) {
                    $value = $this->parseValues($node->value);
                } else {
                    $value = [];
                }
            } else {
                $value = trim($node);
            }

            if (isset($node['key'])) {
                $values[(string) $node['key']] = $value;
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
    protected function parseOptions(\SimpleXMLElement $nodes)
    {
        $options = [];

        foreach ($nodes as $node) {
            if (count($node) > 0) {
                if (count($node->value) > 0) {
                    $value = $this->parseValues($node->value);
                } else {
                    $value = [];
                }
            } else {
                $value = XmlUtils::phpize($node);
                if (is_string($value)) {
                    $value = trim($value);
                }
            }

            $options[(string) $node['name']] = $value;
        }

        return $options;
    }
}
