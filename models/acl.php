<?php
class Acl extends AcpAppModel {

    public $name = 'Acl';

    public $useTable = 'aros_acos';

    public $actsAs = array('Acp.Acl');

}