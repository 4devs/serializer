<?php

namespace FDevs\Serializer\Tests\Visibility;

use FDevs\Serializer\Visibility\Group;

class GroupTest extends VisibilityTest
{
    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return [
            ['property', ['groups' => ['group_name']], ['groups' => ['group_name']], true],
            ['property', ['groups' => ['group_name']], ['groups' => []], false],
            ['property', ['groups' => ['group_name']], ['groups' => ['group']], false],
            ['property', ['groups' => ['group_name'], 'required' => false], ['groups' => ['group']], false],
            ['property', ['groups' => ['group_name'], 'required' => false], [], true],
            ['property', ['groups' => ['group_name', 'name1']], ['groups' => ['name1']], true],
            ['property', ['groups' => ['group_name', 'name1']], ['groups' => ['name1', 'group_name']], true],
            ['property', ['groups' => ['group_name', 'name1']], ['group' => ['name1', 'group_name']], false],
            ['property_name', ['groups' => ['group_name', 'name1']], ['groups' => ['name1', 'group_name']], true],
            ['property_name', ['groups' => ['group_name', 'name1']], ['groups' => ['group_name']], true],
            ['property_name', ['groups' => ['group_name', 'name1'], 'key' => 'group_key'], ['group_key' => ['group_name']], true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        return new Group();
    }
}
