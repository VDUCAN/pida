<?php

class CountryLocale extends AppModel {

    public $name = 'CountryLocale';
    public $validate = array();

    public $belongsTo = array(
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'country_id'
        )
    );

}

?>