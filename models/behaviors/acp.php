<?php
class AcpBehavior extends ModelBehavior {

    private $__types;

    private $__typeMaps = array(
        'requester' => array('Aro'),
        'controlled' => array('Aco'),
        'both' => array('Aro', 'Aco')
    );

    private $__requiredPerms;

    public function setup(&$m, $settings = null) {
        if (is_string($settings)) {
            $settings = array('type' => $settings);
        }

        $this->settings[$m->alias] = Set::merge(
            array(
                'type' => 'controlled',
                'parentClass' => $m->alias,
                'foreignKey' => 'parent_id'
            ),
            $settings
        );

        $type = $this->settings[$m->alias]['type'];
        $this->__types = $this->__typeMaps['controlled'];
        if (array_key_exists($type, $this->__typeMaps)) {
            $this->__types = $this->__typeMaps[$type];
        }
    }

    public function beforeFind(&$m, $queryData) {
        if (in_array('Aco', $this->__types)) {
            $m->bindModel(array('hasMany' => array(
                'Aco' => array(
                    'className' => 'Acp.Aco',
                    'foreignKey' => 'foreign_key',
                    'conditions' => array('model' => $m->alias)
                )
            )));

            $this->__requiredPerms = array('read');
            if (array_key_exists('acl', $queryData)) {
                $this->__requiredPerms = $queryData['acl'];
                unset($queryData['acl']);
            }
        }
        return $queryData;
    }

    public function afterFind(&$m, $results) {
        $acl = ClassRegistry::init('Acp.Acl');
        foreach ($results as $rKey => $result) {
            if (!empty($result['Aco'])) {
                foreach ($result['Aco'] as $aKey => $aco) {
                    if (!$acl->check(null, array('id' => $aco['id']), $this->__requiredPerms)) {
                        unset($result['Aco'][$aKey]);
                    }
                }
            }

            if (empty($result['Aco'])) {
                unset($results[$rKey]);
                continue;
            }
            unset($results[$rKey]['Aco']);
        }
        return $results;
    }

    public function afterSave(&$m, $created) {
        $aro_data = null;
        foreach ($this->__types as $type) {
            $object = ClassRegistry::init('Acp.' . $type);

            $data = array(
                'parent_id' => $this->parentId($m, $object),
                'model' => $m->alias,
                'foreign_key' => $m->id,
                'alias' => $this->alias($m)
            );

            if (!$created) {
                $node = $object->node(array('model' => $m->alias, 'foreign_key' => $m->id));
                if ($node) {
                    $data['id'] = $node[$object->alias]['id'];
                }
            }

            $object->Behaviors->attach('Tree');
            $object->create();
            $object->save(array($object->alias => $data));

            if ($type === 'Aro') {
                $aro_data = $data;
            }

            if ($created && $type === 'Aco') {
                $acl = ClassRegistry::init('Acp.Acl');
                $acl->update($aro_data, $data, '*');
            }
        }
    }

    public function afterDelete(&$m) {
        $record = $this->__record($m);
        if (empty($m->alias) || empty($record[$m->alias]['id'])) {
            return;
        }

        foreach ($this->__types as $type) {
            $object = ClassRegistry::init('Acp.' . $type);
            $object->Behaviors->attach('Tree');

            $nodes = $object->find('all', array('conditions' => array(
                "{$type}.model" => $m->alias,
                "{$type}.foreign_key" => $record[$m->alias]['id']
            )));

            foreach ($nodes as $node) {
                $object->removefromtree($node[$object->alias]['id'], true);
            }
        } 
    }

    public function alias(&$m) {
        $record = $this->__record($m);
        if ($m->displayField !== 'id') {
            if (!empty($record[$m->alias][$m->displayField])) {
                return $record[$m->alias][$m->displayField];
            }
        }
        return null;
    }

    public function parentId(&$m, &$node) {
        $record = $this->__record($m);
        if ($record) {
            $foreignKey = $this->settings[$m->alias]['foreignKey'];
            if (!empty($record[$m->alias][$foreignKey]) && !empty($this->settings[$m->alias]['parentClass'])) {
                if ($parent = $node->find('first', array(
                    'conditions' => array(
                        "{$node->alias}.model" => $this->settings[$m->alias]['parentClass'],
                        "{$node->alias}.foreign_key" => $record[$m->alias][$foreignKey]
                    ),
                    'contain' => false
                ))) {
                    return $parent[$node->alias]['id'];
                }
            }
        }
        return null;
    }

    private function __record(&$m) {
        if (!$m->data) {
            if (!$m->id) {
                return false;
            }
            $m->read();
        }
        return $m->data;
    }

}