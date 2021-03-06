Serializer
==========

[![Build Status](https://travis-ci.org/4devs/serializer.svg?branch=master)](https://travis-ci.org/4devs/serializer)

If you use Symfony 2, you could use our [serializer bridge](https://github.com/4devs/serializer-bridge)!

## Installation
Serializer uses Composer, please checkout the [composer website](http://getcomposer.org) for more information.

The simple following command will install `serializer` into your project. It also add a new
entry in your `composer.json` and update the `composer.lock` as well.


```bash
composer require fdevs/serializer
```

## Usage examples

###create model

```php
<?php

namespace App\Model;

class Post
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var int
     */
    protected $views;

    /**
     * @var bool
     */
    protected $show;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var float
     */
    protected $rate;

    /**
     * @var array|Comment[]
     */
    protected $comments;

    //init geters and setters
}

```

###create mapping

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<serializer xmlns="http://4devs.pro/schema/dic/serializer-mapping"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://4devs.pro/schema/dic/serializer-mapping
        http://4devs.pro/schema/dic/serializer-mapping/serializer-mapping-1.0.xsd">

    <class name="App\Model\Post">
        <option name="camel_to_snake">true</option><!-- if you needed convert name fields to snake case -->

        <property name="_id">
            <option name="serialized-name">id</option><!-- if you needed change serializer name fields -->
            <option name="group"><!-- if you use groups -->
                <value>short</value>
            </option>
        </property>

        <property name="createdAt">
            <type name="datetime"/><!-- set type if field not string -->
        </property>

        <property name="show">
            <type name="boolean"/>
        </property>

        <property name="views">
            <type name="int"/>
        </property>

        <property name="user">
            <type name="object">
                <option name="class">App\Model\User</option>
            </type>
        </property>

        <property name="rate">
            <type name="float"/>
        </property>

        <property name="comments">
            <type name="collection">
                <option name="type">object</option>
                <option name="options">
                    <value key="class">App\Model\Comment</value>
                </option>
            </type>
        </property>

    </class>

</serializer>
```

### init Serializer

```php

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use FDevs\Serializer\Normalizer\ObjectNormalizer as FDevsNormalizer;
use FDevs\Serializer\Mapping\Factory\LazyLoadingMetadataFactory;
use FDevs\Serializer\Mapping\Loader\XmlFilesLoader;
use App\Model\Post;

$files = ['/path/to/xml/Model.Post.xml'];
$loader = new XmlFilesLoader($files);

//init metadata factory
$loadingMetadata = new LazyLoadingMetadataFactory($loader);


$encoders = array(new XmlEncoder(), new JsonEncoder());
$normalizers = array(new FDevsNormalizer($loadingMetadata), new ObjectNormalizer());

$serializer = new Serializer($normalizers, $encoders);

//init $post
$post = new Post();
echo $serializer->serialize($post);

//get your array data
$data = [];
$post = $serializer->serialize($data,Post::class);
```

## Options

options are of two types NameConverting and Visible
options can be used at the level of properties and class

NameConverting are used to change the name:
 - camel_to_snake
 - name-prefix
 - serialized-name

Visible is used to display the value:
 - fields
 - group
 

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<serializer xmlns="http://4devs.pro/schema/dic/serializer-mapping"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://4devs.pro/schema/dic/serializer-mapping
        http://4devs.pro/schema/dic/serializer-mapping/serializer-mapping-1.0.xsd">

    <class name="App\Model\Post">
        <option name="camel_to_snake">true</option><!-- if you needed convert name fields to snake case -->
        <option name="fields"/> <!-- if you needed filters field in request -->
        
        <property name="_id">
            <option name="serialized-name">id</option><!-- if you needed change serializer name fields -->
            <option name="group"><!-- if you use groups -->
                <value>short</value>
            </option>
        </property>

        <property name="createdAt">
            <option name="name-prefix">pre_</option><!-- if you needed add prefix serialized name -->
            <type name="datetime"/><!-- set type if field not string -->
        </property>
    </class>

</serializer>
```

### add Option

#### create your NameConverting option

```php
<?php

namespace App\Serializer\Option\NameConverter;

use FDevs\Serializer\Option\NameConverterInterface;

class NameSuffix implements NameConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function convert($propertyName, array $options, $type = self::TYPE_NORMALIZE)
    {
        return $propertyName.implode('', $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'name-suffix';
    }
}
```

#### create your Visible option

```php
<?php

namespace App\Serializer\Option\Visible;

use FDevs\Serializer\Option\VisibleInterface;

class VersionOption implements VisibleInterface
{
    const CONTEXT_KEY = 'version';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'version';
    }

    /**
     * {@inheritdoc}
     */
    public function isVisible($propertyName, $value, array $options, array $context)
    {
        return !isset($context[static::CONTEXT_KEY]) || !is_array($context[static::CONTEXT_KEY]) || !count($context[static::CONTEXT_KEY]) || $context[static::CONTEXT_KEY] == reset($options);
    }
}
```

#### use options

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<serializer xmlns="http://4devs.pro/schema/dic/serializer-mapping"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://4devs.pro/schema/dic/serializer-mapping
        http://4devs.pro/schema/dic/serializer-mapping/serializer-mapping-1.0.xsd">

    <class name="App\Model\Post">
        <option name="name-suffix">_suffix</option>
        
        <property name="views">
            <option name="version">1.0</option>
            <type name="int"/>
        </property>
    </class>

</serializer>
```

#### add option to the Normalizer

```php
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use FDevs\Serializer\Normalizer\ObjectNormalizer as FDevsNormalizer;
use FDevs\Serializer\Mapping\Factory\LazyLoadingMetadataFactory;
use FDevs\Serializer\Mapping\Loader\XmlFilesLoader;
use FDevs\Serializer\OptionRegistry;
use App\Serializer\Option\NameConverter\NameSuffix;
use App\Serializer\Option\Visible\VersionOption;
use FDevs\Serializer\DataTypeFactory;
use App\Model\Post;

$files = ['/path/to/xml/Model.Post.xml'];
$loader = new XmlFilesLoader($files);

//init metadata factory
$loadingMetadata = new LazyLoadingMetadataFactory($loader);

//create OptionRegistry
$optionRegistry = new OptionRegistry();
$optionRegistry->addOption(new NameSuffix());
$optionRegistry->addOption(new VersionOption());

//create DataTypeFactory
$dataTypeFactory = new DataTypeFactory();

$normalizer = new FDevsNormalizer($loadingMetadata, $dataTypeFactory, $optionRegistry);


$serializer = new Serializer([$normalizer, new ObjectNormalizer()], [new XmlEncoder(), new JsonEncoder()]);
```

License
-------

This library is under the MIT license. See the complete license in the library:

    LICENSE


---
Created by [4devs](http://4devs.pro/) - Check out our [blog](http://4devs.io/) for more insight into this and other open-source projects we release.
