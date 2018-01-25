<?php

/*
 * This file is part of the 4devs Serialiser package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FDevs\Serializer\Mapping\Loader;

use FDevs\Serializer\Exception\MappingException;
use FDevs\Serializer\OptionRegistry;

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
    public function __construct(array $files, OptionRegistry $registry)
    {
        foreach ($files as $class => $file) {
            $this->addFile($class, $file);
        }
        parent::__construct($registry);
    }

    /**
     * @param string $class
     * @param string $file
     *
     * @throws MappingException
     *
     * @return $this
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
