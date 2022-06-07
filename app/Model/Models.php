<?php

class Models extends AppModel {

    public $name = 'Models';

    public $useTable = 'models';

    public $validate = array(
        'make_id' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Select Vehicle Make',
                'allowEmpty' => false
            )
        )
    );

    public $belongsTo = array(
        'Make' => array(
            'className' => 'Make',
            'foreignKey' => 'make_id'
        )
    );

    public $hasMany = array(
        'ModelLocale' => array(
            'className' => 'ModelLocale',
            'foreignKey' => 'model_id'
        )
    );

}

?>