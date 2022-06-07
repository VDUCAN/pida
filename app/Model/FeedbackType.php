<?php

class FeedbackType extends AppModel {

    public $name = 'FeedbackType';
    public $validate = array();

    public $hasMany = array(
        'FeedbackTypeLocale' => array(
            'className' => 'FeedbackTypeLocale',
            'foreignKey' => 'feedback_type_id'
        )
    );

}

?>