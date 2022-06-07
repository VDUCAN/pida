<?php

class StateLocale extends AppModel {

    public $name = 'StateLocale';
    public $validate = array();

    public $belongsTo = array(
        'State' => array(
            'className' => 'State',
            'foreignKey' => 'state_id'
        )
    );

}

?>