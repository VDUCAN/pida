<?php

class DeliveryTypeLocale extends AppModel {

    public $name = 'DeliveryTypeLocale';
    public $validate = array();

    public $belongsTo = array(
        'DeliveryType' => array(
            'className' => 'DeliveryType',
            'foreignKey' => 'delivery_type_id'
        )
    );

}

?>