<?php
App::import('Behavior', 'Acp.Acl');
class AclBehaviorTestCase extends CakeTestCase {

    public $fixtures = array(
        'plugin.acp.aros_aco',
        'plugin.acp.aro',
        'plugin.acp.aco'
    );

    public function startTest() {
		Configure::write('Acl.database', 'test_suite');
		$this->AclBehavior =& new AclBehavior();
		$this->Acl =& ClassRegistry::init('ArosAco');
    }

    public function endTest() {
		ClassRegistry::flush();
		unset($this->Acl, $this->AclBehavior);
    }

    public function testSetup() {
		$this->AclBehavior->setup();
		$this->assertFalse(empty($this->AclBehavior->Aro));
		$this->assertFalse(empty($this->AclBehavior->Aco));
	}

    public function testCheck() {
		$this->AclBehavior->setup();

		$this->assertFalse($this->AclBehavior->check($this->Acl, null, null));
		$this->assertFalse($this->AclBehavior->check($this->Acl, null, 'controllers'));
		$this->assertTrue($this->AclBehavior->check($this->Acl, array('model' => 'User'), null), '*');
		$this->assertFalse($this->AclBehavior->check($this->Acl, false, false));

		$this->assertTrue($this->AclBehavior->check($this->Acl, 'Group:1/User:1', 'controllers/one_tests'));
		$this->assertTrue($this->AclBehavior->check($this->Acl, null, 'controllers/one_tests'));
		$this->assertTrue($this->AclBehavior->check($this->Acl, null, null));
		$this->assertFalse($this->AclBehavior->check($this->Acl, 'Group:4', null));

		$this->assertFalse($this->AclBehavior->check($this->Acl, 'Group:4', 'controllers/four_tests'));
		$this->assertFalse($this->AclBehavior->check($this->Acl, 'Group:2/User:2', 'controllers/one_tests'));
		$this->assertFalse($this->AclBehavior->check($this->Acl, null, 'controllers/one_tests'));
	}

	public function testUpdate() {
		$this->AclBehavior->setup();

		$this->assertFalse($this->AclBehavior->update($this->Acl, false, false));
		$this->assertFalse($this->AclBehavior->update($this->Acl, null, null));
		$this->assertFalse($this->AclBehavior->update($this->Acl, null, 'controllers'));
		$this->assertTrue($this->AclBehavior->update($this->Acl, 'Group:1/User:1', null));

		$this->assertFalse($this->AclBehavior->check($this->Acl, 'Group:3/User:3', 'controllers/three_tests'));
		$this->assertTrue($this->AclBehavior->update($this->Acl, 'Group:3/User:3', 'controllers/three_tests'));
		$this->assertTrue($this->AclBehavior->check($this->Acl, 'Group:3/User:3', 'controllers/three_tests'));
		$this->assertFalse($this->AclBehavior->check($this->Acl, false, false));

		$this->assertFalse($this->AclBehavior->check($this->Acl, 'Group:3/User:3', 'controllers/three_tests', array('read')));
		$this->assertTrue($this->AclBehavior->update($this->Acl, 'Group:3/User:3', 'controllers/three_tests', array('read')));
		$this->assertTrue($this->AclBehavior->check($this->Acl, 'Group:3/User:3', 'controllers/three_tests', array('read')));

		$this->assertFalse($this->AclBehavior->check($this->Acl, 'Group:3/User:3', 'controllers/three_tests', array('create', 'read')));
		$this->assertTrue($this->AclBehavior->update($this->Acl, 'Group:3/User:3', 'controllers/three_tests', array('create', 'read')));
		$this->assertTrue($this->AclBehavior->check($this->Acl, 'Group:3/User:3', 'controllers/three_tests', array('create', 'read')));

		$this->assertFalse($this->AclBehavior->check($this->Acl, 'Group:3/User:3', 'controllers/three_tests', '*'));
		$this->assertTrue($this->AclBehavior->update($this->Acl, 'Group:3/User:3', 'controllers/three_tests', '*'));
		$this->assertTrue($this->AclBehavior->check($this->Acl, 'Group:3/User:3', 'controllers/three_tests', '*'));
	}

	public function testRemove() {
		$this->AclBehavior->setup();

		$this->assertFalse($this->AclBehavior->remove($this->Acl, false, false));
		$this->assertFalse($this->AclBehavior->remove($this->Acl, null, null));

		$this->AclBehavior->update($this->Acl, 'Group:3/User:3', 'controllers/three_tests');
		$this->assertTrue($this->AclBehavior->remove($this->Acl));

		$this->AclBehavior->update($this->Acl, 'Group:3/User:3', 'controllers/three_tests');
		$this->assertFalse($this->AclBehavior->remove($this->Acl, false, false));
		$this->assertFalse($this->AclBehavior->remove($this->Acl));
		$this->assertTrue($this->AclBehavior->remove($this->Acl, 'Group:3/User:3', 'controllers/three_tests'));
		$this->assertFalse($this->AclBehavior->check($this->Acl, 'Group:3/User:3', 'controllers/three_tests'));
	}

}