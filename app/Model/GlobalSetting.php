<?php

class GlobalSetting extends AppModel {

    public $name = 'GlobalSetting';

    public $validate = array(
        'from_email' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Email can\'t be empty',
                'allowEmpty' => false
            ),
            'validEmail' => array(
                'rule' => array('email'),
                'message' => 'Please enter valid email',
                'allowEmpty' => false
            )
        ),
        'to_email' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Email can\'t be empty',
                'allowEmpty' => false
            ),
            'validEmail' => array(
                'rule' => array('email'),
                'message' => 'Please enter valid email',
                'allowEmpty' => false
            )
        ),
        'from_email_text' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'From email text can\'t be empty',
                'allowEmpty' => false
            )
        ),
        'customer_booking_cancel_timeframe' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Enter Cancel Timeframe Minutes',
                'allowEmpty' => false
            ),
            'allowNumberOnly' => array(
                'rule' => '|^[0-9]*$|',
                //'rule' => 'numeric',
                'message' => 'Enter valid minutes',
                'allowEmpty' => false
            )
        ),
/*        'admin_commission' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Enter admin commission',
                'allowEmpty' => false
            ),
            'allowFloatOnly' => array(
                'rule' => array('decimal',2),
                'message' => 'Enter valid commission in %',
                'allowEmpty' => false
            )
        ) */
        'admin_pida_fee' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Enter pida fees',
                'allowEmpty' => false
            ),
            'allowNumberOnly' => array(
                'rule' => array('decimal'),
                'message' => 'Enter valid pida fees',
                'allowEmpty' => false
            )
        ),
        'per_mile_fare' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Enter per mile fare',
                'allowEmpty' => false
            ),
            'allowNumberOnly' => array(
                'rule' => array('decimal'),
                'message' => 'Enter valid per mile fare',
                'allowEmpty' => false
            )
        ),
        'minimum_fare' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Enter minimum fare',
                'allowEmpty' => false
            ),
            'allowNumberOnly' => array(
                'rule' => array('decimal'),
                'message' => 'Enter valid minimum fare',
                'allowEmpty' => false
            )
        ),
        'minimum_fare_distance' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Enter minimum fare distance',
                'allowEmpty' => false
            ),
            'allowNumberOnly' => array(
                'rule' => array('decimal'),
                'message' => 'Enter valid minimum fare distance',
                'allowEmpty' => false
            )
        ), 

    );

}
