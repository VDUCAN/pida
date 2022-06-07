<?php

class Make extends AppModel {

    public $name = 'Make';
    public $validate = array();

    public $hasMany = array(
        'MakeLocale' => array(
            'className' => 'MakeLocale',
            'foreignKey' => 'make_id'
        )
    );

}

?>