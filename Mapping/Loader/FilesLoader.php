<?php

namespace FDevs\Serializer\Mapping\Loader;

use FDevs\Serializer\Exception\MappingException;

abstract class FilesLoader extends AbstractLoader implements LoaderInterface
{
    /**
     * @var string
     */
    protected $files;

    /**
     * Constructor.
     *
     * @param array $files The mapping files to load [class => file]
     *
     * @throws MappingException if the mapping file does not exist or is not readable
     */
    public function __construct(array $files = [])
    {
        foreach ($files as $class => $file) {
            $this->addFile($class, $file);
        }
    }

    /**
     * @param string $class
     * @param string $file
     *
     * @return $this
     *
     * @throws MappingException
     */
    public function addFile($class, $file)
    {
        if (!is_file($file)) {
            throw new MappingException(sprintf('The mapping file %s does not exist', $file));
        }

        if (!is_readable($file)) {
            throw new MappingException(sprintf('The mapping file %s is not readable', $file));
        }
        $this->files[$class] = $file;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasMetadata($class)
    {
        return isset($this->files[$class]);
    }
}
