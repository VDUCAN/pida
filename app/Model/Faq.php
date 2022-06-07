<?php

class Faq extends AppModel {

    public $name = 'Faq';
    public $validate = array();

    public $hasMany = array(
        'FaqLocale' => array(
            'className' => 'FaqLocale',
            'foreignKey' => 'faq_id'
        )
    );

}

?>