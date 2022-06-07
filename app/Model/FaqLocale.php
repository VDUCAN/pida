<?php

class FaqLocale extends AppModel {

    public $name = 'FaqLocale';
    public $validate = array();

    public $belongsTo = array(
        'Faq' => array(
            'className' => 'Faq',
            'foreignKey' => 'faq_id'
        )
    );

}

?>
