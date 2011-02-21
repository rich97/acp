<?php
class UserFixture extends CakeTestFixture {

    public $name = 'User';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'group_id' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 10),
        'email' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 255),
        'username' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 255),
        'password' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 255),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null)
    );

    public $records = array(
        array('id' => 1, 'group_id' => 1, 'email' => 'test1@test.com', 'username' => 'test1', 'password' => 'a_password', 'created' => '00-00-0000 00:00:00', 'modified' => '00-00-0000 00:00:00'),
        array('id' => 2, 'group_id' => 2, 'email' => 'test2@test.com', 'username' => 'test2', 'password' => 'a_password', 'created' => '00-00-0000 00:00:00', 'modified' => '00-00-0000 00:00:00'),
        array('id' => 3, 'group_id' => 3, 'email' => 'test3@test.com', 'username' => 'test3', 'password' => 'a_password', 'created' => '00-00-0000 00:00:00', 'modified' => '00-00-0000 00:00:00')
    );

}