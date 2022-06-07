<?php

class DriverQuestion extends AppModel {

    public $name = 'DriverQuestion';

    public $validate = array(
        'driver_id' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Select Driver',
                'allowEmpty' => false
            )
        ),
        'question' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Enter Question',
                'allowEmpty' => false
            )
        ),
        'answer' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Enter Answer',
                'allowEmpty' => false
            )
        )
    );
   
}
