<?php
class FeedbackRequest extends AppModel {

    public $name = 'FeedbackRequest';
    public $validate = array(
        'reply_msg' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Enter reply message',
                'allowEmpty' => false
            )
        )
    );

//    public $hasMany = array(
//        'FeedbackTypeLocale' => array(
//            'className' => 'FeedbackTypeLocale',
//            'foreignKey' => 'feedback_type_id'
//        )
//    );
  
}