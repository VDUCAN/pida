<?php

class State extends AppModel {

    public $name = 'State';
    public $validate = array(
        'country_id' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Select Country',
                'allowEmpty' => false
            )
        )
    );

    public $hasMany = array(
        'StateLocale' => array(
            'className' => 'StateLocale',
            'foreignKey' => 'state_id'
        )
    );

    public $belongsTo = array(
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'country_id'
        )
    );

}

?>