<?php

class DeliveryType extends AppModel {

    public $name = 'DeliveryType';
    public $validate = array();

    public $hasMany = array(
        'DeliveryTypeLocale' => array(
            'className' => 'DeliveryTypeLocale',
            'foreignKey' => 'delivery_type_id'
        )
    );

}

?>