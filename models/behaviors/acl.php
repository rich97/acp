<?php
class AclBehavior extends ModelBehavior {

    public $Aro, $Aco;

    public function setup() {
        $this->Aro =& ClassRegistry::init('Acp.Aro');
        $this->Aco =& ClassRegistry::init('Acp.Aco');
    }

    public function check(&$m, $aro = null, $aco = null, $modes = array()) {
        $this->Aro->node($aro);
        $this->Aco->node($aco);

        $aros = $this->Aro->get();
        $acos = $this->Aco->get();

        if ($aros && $acos) {
            $m->Behaviors->attach('Containable');
            foreach ($aros as $requester_nodes) {
                $request_id = Set::extract('/Aro/id', $requester_nodes);
                foreach ($acos as $controlled_nodes) {
                    $controlled_ids = Set::extract('/Aco/id', $controlled_nodes);
                    foreach ($request_id as $id) {
                        $conditions = array(
                            'conditions' => array(
                                "{$m->alias}.aro_id" => $id,
                                "{$m->alias}.aco_id" => $controlled_ids
                            ),
                            'contain' => false
                        );

                        if ($result = $m->find('all', $conditions)) {
                            $required = $modes;
                            if (!$required) {
                                return true;
                            }

                            if ($required === '*') {
                                $required = array('create', 'read', 'update', 'delete');
                            }

                            foreach ($result as $acl) {
                                $acl = $acl[$m->alias];
                                foreach ($required as $key => $mode) {
                                    if (!empty($acl['_' . $mode])) {
                                        unset($required[$key]);
                                    }
                                }

                                if (empty($required)) {
                                    return true;
                                }
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    public function update(&$m, $aro = null, $aco = null, $modes = array()) {
        $this->Aro->node($aro);
        $this->Aco->node($aco);

        $aros = $this->Aro->get();
        $acos = $this->Aco->get();

        if ($aros && $acos) {
            $m->Behaviors->attach('Containable');

            foreach ($aros as $requester_nodes) {
                $save = false;
                $requester = end($requester_nodes);

                foreach ($acos as $controlled_nodes) {
                    $controlled = end($controlled_nodes);

                    $data[$m->alias]['id'] = null;
                    $data[$m->alias]['aro_id'] = $requester['Aro']['id'];
                    $data[$m->alias]['aco_id'] = $controlled['Aco']['id'];

                    if ($result = $m->find('first', array(
                        'conditions' => array(
                            "{$m->alias}.aro_id" => $requester['Aro']['id'],
                            "{$m->alias}.aco_id" => $controlled['Aco']['id']
                        ),
                        'contain' => false,
                        'fields' => array('id')
                    ))) {
                        $data[$m->alias]['id'] = $result[$m->alias]['id'];
                    }

                    if ($modes === '*') {
                        $modes = array('create', 'read', 'update', 'delete');
                    }

                    foreach ($modes as $mode) {
                        $data[$m->alias]['_' . $mode] = 1;
                    }

                    $defaults = array('_create' => 0, '_read' => 0, '_update' => 0, '_delete' => 0);
                    $data[$m->alias] = Set::merge($defaults, $data[$m->alias]);

                    $save = $m->save($data);
                }
            }
            return (boolean) $save;
        }
        return false;
    }

    public function remove(&$m, $aro = null, $aco = null) {
        $this->Aro->node($aro);
        $this->Aco->node($aco);

        $aros = $this->Aro->get();
        $acos = $this->Aco->get();

        if ($aros && $acos) {
            $m->Behaviors->attach('Containable');

            foreach ($aros as $requester_nodes) {
                $deleted = false;
                $requester = end($requester_nodes);

                foreach ($acos as $controlled_nodes) {
                    $controlled = end($controlled_nodes);

                    if ($result = $m->find('first', array(
                        'conditions' => array(
                            "{$m->alias}.aro_id" => $requester['Aro']['id'],
                            "{$m->alias}.aco_id" => $controlled['Aco']['id']
                        ),
                        'contain' => false,
                        'fields' => array('id')
                    ))) {
                        $deleted = $m->delete($result[$m->alias]['id']);
                    }
                }
            }
            return $deleted;
        }
        return false;
    }

}