<?php
class ArosAcoFixture extends CakeTestFixture {

	public $name = 'ArosAco';

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
        'aro_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10),
        'aco_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10),
        '_create' => array('type' => 'string', 'null' => false, 'default' => 0, 'length' => 2),
        '_read' => array('type' => 'string', 'null' => false, 'default' => 0, 'length' => 2),
        '_update' => array('type' => 'string','null' => false, 'default' => 0, 'length' => 2),
        '_delete' => array('type' => 'string', 'null' => false, 'default' => 0, 'length' => 2),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_bin', 'engine' => 'MyISAM')
    );

    public $records = array(
        array('id' => 1, 'aro_id' => 1, 'aco_id' => 1, '_create' => 0, '_read' => 0, '_update' => 0, '_delete' => 0),
        array('id' => 2, 'aro_id' => 1, 'aco_id' => 2, '_create' => 1, '_read' => 1, '_update' => 0, '_delete' => 0),
        array('id' => 3, 'aro_id' => 1, 'aco_id' => 3, '_create' => 1, '_read' => 0, '_update' => 0, '_delete' => 0),
        array('id' => 4, 'aro_id' => 1, 'aco_id' => 4, '_create' => 0, '_read' => 0, '_update' => 0, '_delete' => 0),
        array('id' => 5, 'aro_id' => 2, 'aco_id' => 1, '_create' => 0, '_read' => 0, '_update' => 0, '_delete' => 0),
        array('id' => 6, 'aro_id' => 2, 'aco_id' => 2, '_create' => 1, '_read' => 1, '_update' => 0, '_delete' => 0),
        array('id' => 7, 'aro_id' => 2, 'aco_id' => 3, '_create' => 1, '_read' => 0, '_update' => 0, '_delete' => 0),
        array('id' => 8, 'aro_id' => 2, 'aco_id' => 4, '_create' => 0, '_read' => 0, '_update' => 0, '_delete' => 0),
        array('id' => 9, 'aro_id' => 7, 'aco_id' => 14, '_create' => 1, '_read' => 1, '_update' => 1, '_delete' => 1),
        array('id' => 10, 'aro_id' => 7, 'aco_id' => 15, '_create' => 1, '_read' => 1, '_update' => 0, '_delete' => 0),
        array('id' => 11, 'aro_id' => 7, 'aco_id' => 16, '_create' => 1, '_read' => 0, '_update' => 0, '_delete' => 0),
        array('id' => 12, 'aro_id' => 7, 'aco_id' => 17, '_create' => 0, '_read' => 0, '_update' => 0, '_delete' => 0),
        array('id' => 13, 'aro_id' => 11, 'aco_id' => 18, '_create' => 1, '_read' => 1, '_update' => 1, '_delete' => 1),
        array('id' => 14, 'aro_id' => 11, 'aco_id' => 19, '_create' => 1, '_read' => 1, '_update' => 0, '_delete' => 0),
        array('id' => 15, 'aro_id' => 11, 'aco_id' => 20, '_create' => 1, '_read' => 0, '_update' => 0, '_delete' => 0),
        array('id' => 16, 'aro_id' => 11, 'aco_id' => 21, '_create' => 0, '_read' => 0, '_update' => 0, '_delete' => 0)
    );

}
