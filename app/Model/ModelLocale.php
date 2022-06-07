<?php

class ModelLocale extends AppModel {

    public $name = 'ModelLocale';
    public $validate = array();

    public $belongsTo = array(
        'Models' => array(
            'className' => 'Models',
            'foreignKey' => 'model_id'
        )
    );

}

?>