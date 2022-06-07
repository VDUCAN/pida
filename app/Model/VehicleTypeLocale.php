<?php

class VehicleTypeLocale extends AppModel {

    public $name = 'VehicleTypeLocale';
    public $validate = array();

    public $belongsTo = array(
        'VehicleType' => array(
            'className' => 'VehicleType',
            'foreignKey' => 'vehicle_type_id'
        )
    );

}

?>