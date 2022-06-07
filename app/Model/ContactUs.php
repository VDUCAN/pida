<?php

class ContactUs extends AppModel {

    public $name = 'ContactUs';
    public $validate = array(
        'first_name' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Enter First Name',
                'allowEmpty' => false
            )
        ),
        'last_name' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Select Last Name',
                'allowEmpty' => false
            )
        ),
        'email' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Enter Email',
                'allowEmpty' => false
            ),
            'validEmail' => array(
                'rule' => array('email'),
                'message' => 'Please Enter Valid Email',
                'allowEmpty' => false
            ),
        ),
        'reason' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Select Reason',
                'allowEmpty' => false
            )
        ),
        'type' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Select Type',
                'allowEmpty' => false
            )
        ),
        'message' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Enter Message',
                'allowEmpty' => false
            )
        )
    );


}

?>
