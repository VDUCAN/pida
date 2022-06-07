<?php

class CargoTypeLocale extends AppModel {

    public $name = 'CargoTypeLocale';
    public $validate = array();

    public $belongsTo = array(
        'CargoType' => array(
            'className' => 'CargoType',
            'foreignKey' => 'cargo_type_id'
        )
    );

}

?>