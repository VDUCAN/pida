<?php

class CargoType extends AppModel {

    public $name = 'CargoType';
    public $validate = array();

    public $hasMany = array(
        'CargoTypeLocale' => array(
            'className' => 'CargoTypeLocale',
            'foreignKey' => 'cargo_type_id'
        )
    );

}

?>