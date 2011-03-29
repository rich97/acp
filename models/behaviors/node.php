<?php
class NodeBehavior extends ModelBehavior {

    private static $__node, $__extra;

    public function node(&$m, $conditions) {
        if ($conditions) {
            $n = $e = array();
            if (is_string($conditions) && $conditions !== '') {
                $refs = explode('/', $conditions);
                foreach ($refs as $key => $alias) {
                    if (!$alias) {
                        continue;
                    }

                    $parent_id = $model = $foreign_key = null;
                    if (!empty($n[0])) {
                        $parent = end($n[0]);
                        $parent_id = $parent[$m->alias]['id'];
                    }
                    if (preg_match("/^([A-Z]{1})([A-z0-9]*)(:{1})([0-9])*$/", $alias)) {
                        list($model, $foreign_key) = explode(':', $alias);
                        $alias = null;
                    }

                    $options = array(
                        'conditions' => array(
                            "{$m->alias}.parent_id" => $parent_id,
                            "{$m->alias}.model" => $model,
                            "{$m->alias}.foreign_key" => $foreign_key,
                            "{$m->alias}.alias" => $alias
                        ),
                        'order' => "{$m->alias}.lft ASC",
                        'recursive' => -1
                    );

                    if ($find = $m->find('first', $options)) {
                        $n[0][] = $find;
                    } else {
                        $e = array_slice($refs, $key);
                    }
                }
            } elseif (is_array($conditions)) {
                if ($refs = $m->find('all', array('conditions' => $conditions))) {
                    $m->Behaviors->attach('Tree');
                    foreach ($refs as $key => $ref) {
                        $n[] = $m->getPath($ref[$m->alias]['id']);
                    }
                }
            }

            NodeBehavior::$__node[$m->alias] = $n;
            NodeBehavior::$__extra[$m->alias] = $e;
        }

        if ($conditions === false) {
            NodeBehavior::$__node[$m->alias] = array();
            NodeBehavior::$__extra[$m->alias] = array();
        }

        if (!empty(NodeBehavior::$__node[$m->alias])) {
            if (empty(NodeBehavior::$__extra[$m->alias])) {
                return true;
            }
        }
        return false;
    }

    public function get(&$m, $string = false) {
        $return = array();
        if (!empty(NodeBehavior::$__node[$m->alias])) {
            $return = NodeBehavior::$__node[$m->alias];
            if ($string) {
                foreach ($return as $key => $nodes) {
                    foreach ($nodes as $part) {
                        $part = $part[$m->alias];
                        if ($part['model'] && $part['foreign_key']) {
                            $path[] = "{$part['model']}:{$part['foreign_key']}";
                        } else {
                            $path[] = $part['alias'];
                        }
                    }
                    $return[$key] = implode('/', $path);
                }
            }
        }
        return $return;
    }

    public function update(&$m, $conditions) {
        if (!is_string($conditions)) {
            return false;
        }

        if (!$this->node($m, $conditions)) {
            $n = (array) $this->get($m);
			if (count($n < 1)) {
				$n[][][$m->alias]['id'] = null;
			}

            $e = NodeBehavior::$__extra[$m->alias];
            foreach ($n as $node) {
                $parent_id = null;
                if ($node) {
                    $parent = end($node);
                    $parent_id = $parent[$m->alias]['id'];
                }

                $m->Behaviors->attach('Tree');
                foreach ($e as $alias) {
                    $model = $foreign_key = null;
                    if (preg_match("/^([A-Z]{1})([A-z0-9]*)(:{1})([0-9])*$/", $alias)) {
                        list($model, $foreign_key) = explode(':', $alias);
                        $alias = null;
                    }

                    $m->create();
                    $save = $m->save(array(
                        $m->alias => array(
                            'parent_id' => $parent_id,
                            'model' => $model,
                            'foreign_key' => $foreign_key,
                            'alias' => $alias
                        )
                    ));
                    $parent_id = $m->id;
                }
            }
            return true;
        }
        return false;
    }

    public function remove(&$m, $conditions) {
        if ($this->node(&$m, $conditions) === true) {
            $m->Behaviors->attach('Tree');
            $n = $this->get($m);
            if (count($n) === 1) {
                foreach ($n as $key => $node) {
                    $remove = array_pop($n[$key]);
                    $m->removefromtree($remove[$m->alias]['id']);
                }
                $this->node = $n;
                return true;
            }
        }
        return false;
    }

}
