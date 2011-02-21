<?php
class AcoFixture extends CakeTestFixture {

	public $name = 'Aco';

	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'alias' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_bin', 'engine' => 'MyISAM')
	);

    public $records = array(
        array('id' => 1, 'parent_id' => NULL, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'controllers', 'lft' => 1, 'rght' => 26),
        array('id' => 2, 'parent_id' => 1, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'one_tests', 'lft' => 2, 'rght' => 9),
        array('id' => 3, 'parent_id' => 2, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'one_action', 'lft' => 3, 'rght' => 4),
        array('id' => 4, 'parent_id' => 2, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'two_action', 'lft' => 5, 'rght' => 6),
        array('id' => 5, 'parent_id' => 2, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'three_action', 'lft' => 7, 'rght' => 8),
        array('id' => 6, 'parent_id' => 1, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'two_tests', 'lft' => 10, 'rght' => 17),
        array('id' => 7, 'parent_id' => 6, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'one_action', 'lft' => 11, 'rght' => 12),
        array('id' => 8, 'parent_id' => 6, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'two_action', 'lft' => 13, 'rght' => 14),
        array('id' => 9, 'parent_id' => 6, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'three_action', 'lft' => 15, 'rght' => 16),
        array('id' => 10, 'parent_id' => 1, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'three_tests', 'lft' => 18, 'rght' => 25),
        array('id' => 11, 'parent_id' => 10, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'one_action', 'lft' => 19, 'rght' => 20),
        array('id' => 12, 'parent_id' => 10, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'two_action', 'lft' => 21, 'rght' => 22),
        array('id' => 13, 'parent_id' => 10, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'three_action', 'lft' => 23, 'rght' => 24),
        array('id' => 14, 'parent_id' => null, 'model' => 'Group', 'foreign_key' => 1, 'alias' => null, 'lft' => 27, 'rght' => 30),
        array('id' => 15, 'parent_id' => 14, 'model' => 'User', 'foreign_key' => 1, 'alias' => null, 'lft' => 28, 'rght' => 29),
        array('id' => 16, 'parent_id' => null, 'model' => 'Group', 'foreign_key' => 2, 'alias' => null, 'lft' => 31, 'rght' => 34),
        array('id' => 17, 'parent_id' => 16, 'model' => 'User', 'foreign_key' => 2, 'alias' => null, 'lft' => 32, 'rght' => 33),
        array('id' => 18, 'parent_id' => null, 'model' => 'Group', 'foreign_key' => 3, 'alias' => null, 'lft' => 35, 'rght' => 38),
        array('id' => 19, 'parent_id' => 17, 'model' => 'User', 'foreign_key' => 3, 'alias' => null, 'lft' => 36, 'rght' => 37)
    );

}