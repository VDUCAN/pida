<?php

class MakeLocale extends AppModel {

    public $name = 'MakeLocale';
    public $validate = array();

    public $belongsTo = array(
        'Make' => array(
            'className' => 'Make',
            'foreignKey' => 'make_id'
        )
    );

}

?>