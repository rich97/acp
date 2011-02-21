<?php
App::import('Behavior', 'Acp.Node');

class NodeBehaviorTestCase extends CakeTestCase {

    public $fixtures = array(
		'plugin.acp.aro'
	);

    public function startTest() {
		$this->NodeBehavior =& new NodeBehavior();
		$this->Aro =& ClassRegistry::init('Aro');
    }

    public function endTest() {
		unset($this->NodeBehavior, $this->Aro);
		ClassRegistry::flush();
    }

	public function testSetFromPath() {
		$this->assertFalse($this->NodeBehavior->node($this->Aro, null));
		$this->assertFalse($this->NodeBehavior->node($this->Aro, 'controllers/four_tests'));

		$this->assertTrue($this->NodeBehavior->node($this->Aro, 'controllers/one_tests'));
		$this->assertTrue($this->NodeBehavior->node($this->Aro, 'controllers//one_tests'));
		$this->assertTrue($this->NodeBehavior->node($this->Aro, 'Group:1'));
		$this->assertTrue($this->NodeBehavior->node($this->Aro, 'Group:1/User:1'));
		$this->assertTrue($this->NodeBehavior->node($this->Aro, 'Group:1//User:1'));
		$this->assertTrue($this->NodeBehavior->node($this->Aro, ''));
		$this->assertTrue($this->NodeBehavior->node($this->Aro, null));

		$this->assertFalse($this->NodeBehavior->node($this->Aro, false));
	}

	public function testSetFromModel() {
		$this->assertTrue($this->NodeBehavior->node($this->Aro, array('model' => 'Group')));
		$this->assertTrue($this->NodeBehavior->node($this->Aro, array('model' => 'Group', 'id' => 1)));
		$this->assertTrue($this->NodeBehavior->node($this->Aro, array()));
		$this->assertFalse($this->NodeBehavior->node($this->Aro, array('model' => 'Group', 'id' => 4)));
	}

	public function testGet() {
		$result = $this->NodeBehavior->get($this->Aro);
		$this->assertTrue(empty($result));
		$this->assertTrue(is_array($result));

		$this->NodeBehavior->node($this->Aro, 'controllers/one_tests');
		$result = $this->NodeBehavior->get($this->Aro, false);
		$this->assertTrue(is_array($result[0]));
		$result = $this->NodeBehavior->get($this->Aro, true);
		$this->assertTrue(is_string($result[0]));
		$this->assertTrue(in_array('controllers/one_tests', $result));

		$this->assertEqual($this->NodeBehavior->get($this->Aro, false), array(array(
			array('Aro' =>  array('id' => 7, 'parent_id' => null, 'model' => null, 'foreign_key' => null, 'alias' => 'controllers', 'lft' => 13, 'rght' => 38)),
			array('Aro' =>  array('id' => 8, 'parent_id' => 7, 'model' => null, 'foreign_key' => null, 'alias' => 'one_tests', 'lft' => 14, 'rght' => 21))
		)));
		$this->assertEqual($this->NodeBehavior->get($this->Aro, true), array('controllers/one_tests'));

		$this->NodeBehavior->node($this->Aro, 'Group:1/User:1');
		$this->assertEqual($this->NodeBehavior->get($this->Aro, false), array(array(
			array('Aro' =>  array('id' => 1, 'parent_id' => NULL, 'model' => 'Group', 'foreign_key' => 1, 'alias' => NULL, 'lft' => 1, 'rght' => 4)),
			array('Aro' =>  array('id' => 2, 'parent_id' => 1, 'model' => 'User', 'foreign_key' => 1, 'alias' => NULL, 'lft' => 2, 'rght' => 3))
		)));
		$this->assertEqual($this->NodeBehavior->get($this->Aro, true), array('Group:1/User:1'));
	}

	public function testUpdate() {
		$this->assertFalse($this->NodeBehavior->node($this->Aro, 'controllers/four_tests'));
		$this->assertTrue($this->NodeBehavior->update($this->Aro, 'controllers/four_tests'));
		$this->assertTrue($this->NodeBehavior->node($this->Aro, 'controllers/four_tests'));

		$this->assertFalse($this->NodeBehavior->node($this->Aro, 'Group:1/User:2'));
		$this->assertTrue($this->NodeBehavior->update($this->Aro, 'Group:1/User:2'));
		$this->assertTrue($this->NodeBehavior->node($this->Aro, 'Group:1/User:2'));

		$this->assertFalse($this->NodeBehavior->update($this->Aro, array()));
		$this->assertFalse($this->NodeBehavior->update($this->Aro, 'controllers'));
	}

	public function testRemove() {
		$this->assertTrue($this->NodeBehavior->update($this->Aro, 'controllers/four_tests'));
		$this->assertTrue($this->NodeBehavior->remove($this->Aro, 'controllers/four_tests'));
		$this->assertFalse($this->NodeBehavior->remove($this->Aro, 'controllers/four_tests'));
	}

}