<?php

class FeedbackTypeLocale extends AppModel {

    public $name = 'FeedbackTypeLocale';
    public $validate = array();

    public $belongsTo = array(
        'FeedbackType' => array(
            'className' => 'FeedbackType',
            'foreignKey' => 'feedback_type_id'
        )
    );

}

?>