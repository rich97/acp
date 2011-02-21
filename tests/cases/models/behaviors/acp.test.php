<?php
App::import('Behavior', 'Acp.Acp');

class Group extends CakeTestModel {

	public $name = 'Group';

	public $hasMany = array('User');

}

class User extends CakeTestModel {

	public $name = 'User';

	public $belongsTo = array('Group');

}

class AcpBehaviorTestCase extends CakeTestCase {

    public $fixtures = array(
        'plugin.acp.aros_aco',
        'plugin.acp.aco',
        'plugin.acp.aro',
        'plugin.acp.user',
        'plugin.acp.group'
	);

    public function startTest() {
		$this->AcpBehavior =& new AcpBehavior();
		$this->User =& new User();
    }

    public function endTest() {
		ClassRegistry::flush();
		unset($this->AcpBehavior, $this->User);
    }

	public function testSetup() {
		$controlled = array(
			'type' => 'controlled',
			'parentClass' => $this->User->alias,
			'foreignKey' => 'parent_id'
		);
		$this->AcpBehavior->setup($this->User);
		$this->assertEqual($this->AcpBehavior->settings[$this->User->alias], $controlled);
		$this->AcpBehavior->setup($this->User, 'controlled');
		$this->assertEqual($this->AcpBehavior->settings[$this->User->alias], $controlled);
		$this->AcpBehavior->setup($this->User, array('type' => 'controlled'));
		$this->assertEqual($this->AcpBehavior->settings[$this->User->alias], $controlled);

		$requester = array(
			'type' => 'requester',
			'parentClass' => $this->User->alias,
			'foreignKey' => 'parent_id'
		);
		$this->AcpBehavior->setup($this->User, 'requester');
		$this->assertEqual($this->AcpBehavior->settings[$this->User->alias], $requester);
		$this->AcpBehavior->setup($this->User, array('type' => 'requester'));
		$this->assertEqual($this->AcpBehavior->settings[$this->User->alias], $requester);

		$this->AcpBehavior->setup($this->User, array(
			'type' => 'requester',
			'parentClass' => 'Group',
			'foreignKey' => 'group_id',
		));
		$this->assertEqual($this->AcpBehavior->settings[$this->User->alias], array(
			'type' => 'requester',
			'parentClass' => 'Group',
			'foreignKey' => 'group_id'
		));
	}

	public function testBeforeFind() {
		$this->AcpBehavior->setup($this->User, 'both');

		$result = $this->AcpBehavior->beforeFind($this->User, array());
		$this->assertTrue(empty($result));
		$this->assertTrue(is_array($result));

		$result = $this->AcpBehavior->beforeFind($this->User, array('acl' => array('read', 'create')));
		$this->assertTrue(empty($result));
		$this->assertTrue(is_array($result));

		$this->assertFalse(empty($this->User->Aco));
	}

	public function testAfterFind() {
		$aro = ClassRegistry::init('Acp.Aro');
		$acl = ClassRegistry::init('Acp.Acl');
		$aro->node('Group:1/User:1');

		$this->AcpBehavior->setup($this->User, 'both');
		$user = $this->User->find('all', $this->AcpBehavior->beforeFind(
			$this->User,
			array('conditions' => array(
				'User.id' => 1
			))
		));

		$result = $this->AcpBehavior->afterFind($this->User, $user);
		$this->assertTrue(empty($result));
		$this->assertTrue(is_array($result));

		$acl->update('Group:1/User:1', 'Group:1/User:1');
		$result = $this->AcpBehavior->afterFind($this->User, $user);
		$this->assertTrue(empty($result));
		$this->assertTrue(is_array($result));

		$acl->update('Group:1/User:1', 'Group:1/User:1', array('read'));
		$expected = array(array(
			'User' => array('id' => 1, 'group_id' => 1, 'email' => 'test1@test.com', 'username' => 'test1', 'password' => 'a_password', 'created' => '0000-00-00 00:00:00', 'modified' => '0000-00-00 00:00:00'),
			'Group' => array('id' => 1, 'group' => 'test1', 'created' => '0000-00-00 00:00:00', 'modified' => '0000-00-00 00:00:00')
		));
		$this->assertEqual($this->AcpBehavior->afterFind($this->User, $user), $expected);


		$user = $this->User->find('all', $this->AcpBehavior->beforeFind(
			$this->User,
			array(
				'conditions' => array('User.id' => 1),
				'acl' => '*'
			)
		));
		$result = $this->AcpBehavior->afterFind($this->User, $user);
		$this->assertTrue(empty($result));
		$this->assertTrue(is_array($result));

		$acl->update('Group:1/User:1', 'Group:1/User:1', '*');
		$this->assertEqual($this->AcpBehavior->afterFind($this->User, $user), $expected);

		$user = $this->User->find('all', $this->AcpBehavior->beforeFind(
			$this->User,
			array(
				'conditions' => array('User.id' => 1),
				'acl' => array('create', 'read')
			)
		));
		$this->assertEqual($this->AcpBehavior->afterFind($this->User, $user), $expected);
		$acl->update('Group:1/User:1', 'Group:1/User:1', array('create'));
		$result = $this->AcpBehavior->afterFind($this->User, $user);
		$this->assertTrue(empty($result));
		$this->assertTrue(is_array($result));

		$acl->remove('Group:1/User:1', 'Group:1/User:1');
	}

	public function testAfterSave() {
		$aro = ClassRegistry::init('Acp.Aro');
		$aco = ClassRegistry::init('Acp.Aco');
		$acl = ClassRegistry::init('Acp.Acl');

		$this->assertFalse($aro->node('Group:4'));
		$this->assertFalse($aco->node('Group:4'));

		$this->User->Group->create();
		$this->User->Group->save(array('Group' => array(
			'group' => 'test4'
		)));

		$this->AcpBehavior->setup($this->User->Group, 'both');
		$this->AcpBehavior->afterSave($this->User->Group, true);

		$this->assertTrue($aro->node('Group:4'));
		$this->assertTrue($aco->node('Group:4'));
		$this->assertTrue($acl->check('Group:4', 'Group:4', '*'));

		$this->assertFalse($aro->node('Group:4/User:4'));
		$this->assertFalse($aco->node('Group:4/User:4'));

		$this->User->create();
		$this->User->save(array('User' => array(
			'group_id' => '4',
			'username' => 'test4',
			'email' => 'test4@test.com',
			'password' => 'a_password'
		)));

		$this->AcpBehavior->setup($this->User, array(
			'type' => 'both',
			'foreignKey' => 'group_id',
			'parentClass' => 'Group'
		));
		$this->AcpBehavior->afterSave($this->User, true);

		$this->assertTrue($aro->node('Group:4/User:4'));
		$this->assertTrue($aco->node('Group:4/User:4'));
		$this->assertTrue($acl->check('Group:4/User:4', 'Group:4/User:4', '*'));

		$this->User->create();
		$this->User->save(array('User' => array(
			'group_id' => '4',
			'username' => 'test5',
			'email' => 'test5@test.com',
			'password' => 'a_password'
		)));

		$this->AcpBehavior->setup($this->User, array(
			'type' => 'requester',
			'foreignKey' => 'group_id',
			'parentClass' => 'Group'
		));
		$this->AcpBehavior->afterSave($this->User, true);

		$this->assertTrue($aro->node('Group:4/User:5'));
		$this->assertFalse($aco->node('Group:4/User:5'));
		$this->assertTrue($acl->check('Group:4/User:5', 'Group:4', '*'));

		$this->AcpBehavior->afterSave($this->User, false);

		$this->assertTrue($aro->node('Group:4/User:5'));
		$this->assertFalse($aco->node('Group:4/User:5'));
		$this->assertTrue($acl->check('Group:4/User:5', 'Group:4', '*'));
	}

	public function testAfterDelete() {
		$aro = ClassRegistry::init('Acp.Aro');
		$aco = ClassRegistry::init('Acp.Aco');
		$acl = ClassRegistry::init('Acp.Acl');

		$aro->Behaviors->detach('Tree');
		$aco->Behaviors->detach('Tree');

		$this->AcpBehavior->setup($this->User->Group, 'both');

		$this->User->Group->create();
		$this->User->Group->save(array('Group' => array(
			'group' => 'test4'
		)));
		$this->AcpBehavior->afterSave($this->User->Group, true);

		$this->assertTrue($aro->node('Group:4'));
		$this->assertTrue($aco->node('Group:4'));
		$this->assertTrue($acl->check('Group:4', 'Group:4', '*'));

		$this->User->Group->delete(4);
		$this->AcpBehavior->afterDelete($this->User->Group);

		$this->assertFalse($aro->node('Group:4'));
		$this->assertFalse($aco->node('Group:4'));
		$this->assertFalse($acl->check('Group:4', 'Group:4', '*'));

		$this->User->Group->id = null;
		$this->User->Group->data = null;
		$this->AcpBehavior->afterDelete($this->User->Group);
	}

	public function testParentId() {
		$aro = ClassRegistry::init('Acp.Aro');
		$this->AcpBehavior->setup($this->User, array(
			'type' => 'both',
			'foreignKey' => 'group_id',
			'parentClass' => 'Group'
		));

		$this->assertTrue(is_null($this->AcpBehavior->parentId($this->User, $aro)));
		$this->User->id = 1;
		$this->assertEqual($this->AcpBehavior->parentId($this->User, $aro), 1);
		$this->User->id = 2;
		$this->User->data = null;
		$this->assertEqual($this->AcpBehavior->parentId($this->User, $aro), 3);
	}

	public function testAlias() {
		$this->assertTrue(is_null($this->AcpBehavior->alias($this->User)));
		$this->User->id = 1;
		$this->assertTrue(is_null($this->AcpBehavior->alias($this->User)));
		$this->User->id = 1;
		$this->User->displayField = 'username';
		$this->assertEqual($this->AcpBehavior->alias($this->User), 'test1');
	}

}