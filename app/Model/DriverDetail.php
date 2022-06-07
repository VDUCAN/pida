<?php

App::uses('Security', 'Utility');
App::uses('CakeEmail', 'Network/Email');

class DriverDetail extends AppModel {

    public $name = 'DriverDetail';

    public $validate = array(

        'ssn' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Enter SSN',
                'allowEmpty' => false
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'SSN Already Exists'
            )
        ),
        'driving_license_no' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Enter Driving License Number',
                'allowEmpty' => false
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'Driving License Number Already Exists'
            )
        ),
        'dl_doc' => array(
            'upload-file' => array(
                'rule' => array('validate_file', 'dl_doc'),
                'message' => 'Only jpg, jpeg, png, gif Files Allowed'
            )
        )

    );

    public function validate_file($file_data, $field)
    {
        $file_data = array_shift($file_data);

        if (0 === $file_data['error']) {

            $allowed_extensions = array();
            if('dl_doc' == $field){
                $allowed_extensions = array('gif', 'jpg', 'jpeg', 'png');
            }

            $file_name = $file_data['name'];
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            if(!in_array($file_ext, $allowed_extensions)) {
                return false;
            }
        }
        return true;

    }


}
