<?php

class City extends AppModel {

    public $name = 'City';
    public $validate = array(
        'country_id' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Select Country',
                'allowEmpty' => false
            )
        ),
        'state_id' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Select State',
                'allowEmpty' => false
            )
        )
    );

    public $hasMany = array(
        'CityLocale' => array(
            'className' => 'CityLocale',
            'foreignKey' => 'city_id'
        )
    );

    public $belongsTo = array(
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'country_id'
        ),
        'State' => array(
            'className' => 'State',
            'foreignKey' => 'state_id'
        )
    );

}

?>