<?php

class PageLocale extends AppModel {

    public $name = 'PageLocale';
    public $validate = array();

    public $belongsTo = array(
        'Page' => array(
            'className' => 'Page',
            'foreignKey' => 'page_id'
        )
    );

}

?>
