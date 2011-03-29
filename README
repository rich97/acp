Introduction

Acp (Access Control Plugin) is a simplified and unintrusive replacement for the default CakePHP ACL.

Installation

I don't have time to write a full on installation tutorial right now so for the time being I'll show a simple implementation and explain it line-by-line. Note that this example is showing integration with Authsome. But I'm sure it can be adapted to work with CakePHP's auth.

    1.  protected $_allow = array();
    2.
    3.  public function beforeFilter() {
    4.		$currentRoute = array(
    5.			'plugin' => $this->params['plugin'],
    6.			'controller' => $this->params['controller'],
    7.			'action' => $this->params['action']
    8.		);
    9.
    10.		if (!in_array($currentRoute['action'], $this->_allow)) {
    11.			if (!$user = $this->Authsome->get()) {
    12.				// User is not logged in.
    13.			} else {
    14.				$user_id = Authsome::get('User.id');
    15.				$acl_routes = implode('/', array_merge(array('action'), array_values($currentRoute)));
    16.				$acl_user = "model/User:1";
    17.
    18.				if ($user_id === 1) {
    19.					$this->Aro->update($acl_user);
    20.					$this->Aco->update($acl_routes);
    21.					$this->Acl->update($acl_user, $acl_routes);
    22.				}
    23.
    24.				if (!$this->Acl->check($acl_user, $acl_routes)) {
    25.					// User does not have access to this part of the site.
    26.				}
    27.			}
    28.		}
    29. }

Line 1:
	Because Authsome does not provide an allowed actions array/option we do it ourselves. As this is the base controller/AppController I make an empty protected class property and whenever I want an extending controllers action to skip user authentication I simply add this property to the extending class containing an array of actions I want to skip.
Line 3:
	The beforeFilter of the [Plugin]AppController any extending classes must call parent::beforeFilter() if they use it themselves.
Lines 4-8:
	Keep track of where we are in the application on every request.
Line 10:
	This is where we check the value of Line 1 if it is not empty and the action is named as one to skip auth then we dont bother.
Line 12:
	The user authentication has failed, handle it however you want.
Lines 14-16:
	Get the user ID and generate the Path for the Requester and the Controlled objects. Note that the prefix of 'model/' and 'action/' are not required I just think is tidyer.
Lines 18-22:
	During development you are not going to want to be constantly managing Aro and Aco nodes. These few lines mean that if you are logged in as a user with an ID of 1 then you will automatically populate the Aro/Aco/AroAcos tables with your own account, any action you visit and create a relationship between the two.
Line 25:
	The ACL test failed, this $acl_user does not have access to $acl_route. Handle that however you want.

Todo

 * Allow model objects to be stored by alias.
 * Implement unix wildcards in node paths.
 * Docblocks all the way across the sky.
 * 100% test coverage. * DONE!
 * Customizable table object and join table names.
 * Try to force the mysql left JOIN in AcpBehavior to remove the necessity of the afterFind method.
 * Create Post fixture for testing AcpBehavior. * DONE!
 * Make sure all $model in behaviors are $m. * DONE!
 * Remove aro_id from fields in contain conditions.
 * Make AcpBehavior beforeFind act more like nodes in AclBehavior.
 * Build paths to new node when adding new records.
 * Remove node relationships when automatically deleting records in AcpBehavior.
 * [bug] Update method should not always require parent node. * DONE! (Requires test case)
 * Write component with static methods and persistant permissions to make it accessable from anywhere. A la Authsome.
