<?php

class Country extends AppModel {

    public $name = 'Country';
    public $validate = array(
        'country_code' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Enter Country Code',
                'allowEmpty' => false
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'Country Code Already Exists'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 10),
                'message' => 'Country Code Should Be Maximum 10 Characters'
            )
        )
    );

    public $hasMany = array(
        'CountryLocale' => array(
            'className' => 'CountryLocale',
            'foreignKey' => 'country_id'
        )
    );

}

?>