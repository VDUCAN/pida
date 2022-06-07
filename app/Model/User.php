<?php

App::uses('Security', 'Utility');
App::uses('CakeEmail', 'Network/Email');

class User extends AppModel {

    public $name = 'User';

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
                'message' => 'Please Enter Last Name',
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
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'Email Already Exists'
            )
        ),
        'old_password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Password Enter Password',
                'allowEmpty' => false
            ),
            'rule1' => array(
                'rule' => array('old_password_check'),
                'message' => 'Incorrect Old Password',
            )
        ),
        'password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Password Enter Password',
                'allowEmpty' => false
            ),
            'between' => array(
                'rule' => array('between', 5, 20),
                'message' => 'Password Must Be Between Between %d And %d Characters',
            ),
            'rule1' => array(
                'rule' => array('compare_old_new_password'),
                'message' => 'Old And New Password Not Matched',
            )
        ),
        'confirm_password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Enter Confirm Password',
                'allowEmpty' => false
            ),
            'compare' => array(
                'rule' => array('validate_passwords'),
                'message' => 'Password And Confirm Password Not Matched',
            )
        ),
        'phone' => array(
            'allowNumberOnly' => array(
                'rule' => array('allowNumberOnly'),
                'message' => 'Phone Number Should Be Numeric Only',
                'allowEmpty' => false
            ),
            'between' => array(
                'rule' => array('between', 8, 15),
                'message' => 'Contact Number Must Be Between %d And %d Characters',
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'Phone number Already Exists'
            )
        ),
        'user_photo' => array(
            'upload-file' => array(
                'rule' => array('validate_file', 'user_photo'),
                'message' => 'Only jpg, jpeg, png, gif Files Allowed'
            )
        )

    );

    public function validate_passwords() {
        return $this->data[$this->alias]['password'] === $this->data[$this->alias]['confirm_password'];
    }

    //Check new paswword should not be same as existing password
    public function compare_old_new_password(){

        if(isset($this->data[$this->alias]['old_password'])) {
            $old_password = $this->data[$this->alias]['old_password'];
            $new_password = $this->data[$this->alias]['password'];

            return ($old_password == $new_password) ? false : true;
        }
        return true;
    }

    public function allowNumberOnly($number) {
        if (!is_numeric($number['phone'])) {
            return false;
        } else {
            return true;
        }
    }

    public function validate_file($file_data, $field)
    {
        $file_data = array_shift($file_data);

        if (0 === $file_data['error']) {

            $allowed_extensions = array();
            if('user_photo' == $field){
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
