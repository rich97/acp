<?php
class GroupFixture extends CakeTestFixture {

    public $name = 'Group';

    public $fields = array(
        'id' => array(
            'type' => 'integer',
            'key' => 'primary',
            'null' => false
        ),
        'group' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'created' => array(
            'type' => 'datetime',
            'null' => true,
            'default' => null
        ),
        'modified' => array(
            'type' => 'datetime',
            'null' => true,
            'default' => null
        )
    );

    public $records = array(
        array('id' => 1, 'group' => 'test1', 'created' => '00-00-0000 00:00:00', 'modified' => '00-00-0000 00:00:00'),
        array('id' => 2, 'group' => 'test2', 'created' => '00-00-0000 00:00:00', 'modified' => '00-00-0000 00:00:00'),
        array('id' => 3, 'group' => 'test3', 'created' => '00-00-0000 00:00:00', 'modified' => '00-00-0000 00:00:00')
    );

}
