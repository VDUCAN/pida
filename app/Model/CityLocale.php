<?php

class CityLocale extends AppModel {

    public $name = 'CityLocale';
    public $validate = array();

    public $belongsTo = array(
        'City' => array(
            'className' => 'City',
            'foreignKey' => 'city_id'
        )
    );

}

?>