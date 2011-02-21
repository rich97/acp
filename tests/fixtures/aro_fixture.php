<?php
class AroFixture extends CakeTestFixture {

    public $name = 'Aro';

    public $actsAs = array('Acp.Node');

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
	    array('id' => 1, 'parent_id' => NULL, 'model' => 'Group', 'foreign_key' => 1, 'alias' => NULL, 'lft' => 1, 'rght' => 4),
	    array('id' => 2, 'parent_id' => 1, 'model' => 'User', 'foreign_key' => 1, 'alias' => NULL, 'lft' => 2, 'rght' => 3),
	    array('id' => 3, 'parent_id' => NULL, 'model' => 'Group', 'foreign_key' => 2, 'alias' => NULL, 'lft' => 5, 'rght' => 8),
	    array('id' => 4, 'parent_id' => 3, 'model' => 'User', 'foreign_key' => 2, 'alias' => NULL, 'lft' => 6, 'rght' => 7),
	    array('id' => 5, 'parent_id' => NULL, 'model' => 'Group', 'foreign_key' => 3, 'alias' => NULL, 'lft' => 9, 'rght' => 12),
	    array('id' => 6, 'parent_id' => 5, 'model' => 'User', 'foreign_key' => 3, 'alias' => NULL, 'lft' => 10, 'rght' => 11),
	    array('id' => 7, 'parent_id' => null, 'model' => null, 'foreign_key' => null, 'alias' => 'controllers', 'lft' => 13, 'rght' => 38),
	    array('id' => 8, 'parent_id' => 7, 'model' => null, 'foreign_key' => null, 'alias' => 'one_tests', 'lft' => 14, 'rght' => 21),
	    array('id' => 9, 'parent_id' => 8, 'model' => null, 'foreign_key' => null, 'alias' => 'one_action', 'lft' => 15, 'rght' => 16),
	    array('id' => 10, 'parent_id' => 8, 'model' => null, 'foreign_key' => null, 'alias' => 'two_action', 'lft' => 17, 'rght' => 18),
	    array('id' => 11, 'parent_id' => 8, 'model' => null, 'foreign_key' => null, 'alias' => 'three_action', 'lft' => 19, 'rght' => 21),
	    array('id' => 12, 'parent_id' => 7, 'model' => null, 'foreign_key' => null, 'alias' => 'two_tests', 'lft' => 22, 'rght' => 29),
	    array('id' => 13, 'parent_id' => 12, 'model' => null, 'foreign_key' => null, 'alias' => 'one_action', 'lft' => 23, 'rght' => 24),
	    array('id' => 14, 'parent_id' => 12, 'model' => null, 'foreign_key' => null, 'alias' => 'two_action', 'lft' => 25, 'rght' => 26),
	    array('id' => 15, 'parent_id' => 12, 'model' => null, 'foreign_key' => null, 'alias' => 'three_action', 'lft' => 27, 'rght' => 28),
	    array('id' => 16, 'parent_id' => 7, 'model' => null, 'foreign_key' => null, 'alias' => 'three_tests', 'lft' => 30, 'rght' => 37),
	    array('id' => 17, 'parent_id' => 16, 'model' => null, 'foreign_key' => null, 'alias' => 'one_action', 'lft' => 31, 'rght' => 32),
	    array('id' => 18, 'parent_id' => 16, 'model' => null, 'foreign_key' => null, 'alias' => 'two_action', 'lft' => 33, 'rght' => 34),
	    array('id' => 19, 'parent_id' => 16, 'model' => null, 'foreign_key' => null, 'alias' => 'three_action', 'lft' => 35, 'rght' => 36)
	);

}