<?php

App::uses('Security', 'Utility');
App::uses('CakeEmail', 'Network/Email');

class Admin extends AppModel {

    public $name = 'Admin';

    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Name can\'t be empty',
                'allowEmpty' => false
            ),
            'between' => array(
                'rule' => array('between', 3, 50),
                'message' => 'Name must be between %d and %d characters',
            )
        ),
        'email' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Email can\'t be empty',
                'allowEmpty' => false
            ),
            'validEmail' => array(
                'rule' => array('validEmail'),
                'message' => 'Please enter valid email address',
                'allowEmpty' => false
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'Email already exists, please use different one',
                'on' => 'create'
            )
        ),
        'is_admin' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select admin type',
                'allowEmpty' => false
            )
        ),
        'old_password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Password can\'t be empty',
                'allowEmpty' => false
            ),
            'rule1' => array(
                'rule' => array('old_password_check'),
                'message' => 'Incorrect old password',
            )
        ),
        'password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Password can\'t be empty',
                'allowEmpty' => false
            ),
            'between' => array(
                'rule' => array('between', 5, 20),
                'message' => 'Password must be between %d and %d characters',
            ),
            'rule1' => array(
                'rule' => array('compare_old_new_password'),
                'message' => 'Old password and new password can\'t be same',
            )
        ),
        'confirm_password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Confirm password can\'t be empty',
                'allowEmpty' => false
            ),
            'compare' => array(
                'rule' => array('validate_passwords'),
                'message' => 'Password and confirm password not matched',
            )
        ),
        'contact_number' => array(
            'allowNumberOnly' => array(
                'rule' => array('allowNumberOnly'),
                'message' => 'Contact number should be numeric only',
                'allowEmpty' => false
            ),
            'between' => array(
                'rule' => array('between', 8, 15),
                'message' => 'Contact number must be between %d and %d characters only',
            )
        )
    );

    //Check user entered old password is correct
    public function old_password_check(){

        $id = $this->data[$this->alias]['id'];
        $old_password = Security::hash($this->data[$this->alias]['old_password'], 'md5');

        return $this->find('count', array(
            'conditions' => array(
                'Admin.password' => $old_password,
                'Admin.id' => $id
            ),
            'limit' => 1
        ));
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

    public function validEmail($email) {
        $regExp = '/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i';
        if (!preg_match($regExp, $email['email'])) {
            return false;
        } else {
            return true;
        }
    }

    public function validate_passwords() {
		return $this->data[$this->alias]['password'] === $this->data[$this->alias]['confirm_password'];
    }

    public function allowNumberOnly($number) {
        if (!is_numeric($number['contact_number'])) {
            return FALSE;
        } else {
            return true;
        }
    }   
	function ChangePassword() {
		
		$validate1 = array(
				'password'=>array(
								'mustNotEmpty'=>array(
								'rule' => 'notEmpty',
								'message'=> 'Please enter password',
								'last'=>true)
								),
							'confirm_password'=>array(
								'rule1'=>array(
								'rule' => 'notEmpty',
								'message'=> 'Please enter confirm password',
								'on' => 'create'
								),
								'rule2'=>array(
								'rule'=>'matchuserspassword',
								'message'=> 'Password and confirm password does not match.'
								)
								)
			);
			
		$this->validate=$validate1;
		return $this->validates();
	}
	
	public function matchuserspassword(){
		  //return $this->data[$this->alias]['password'] === $this->data[$this->alias]['confirm_password'];
		$password		=	$this->data['User']['password'];
		$temppassword	=	$this->data['User']['confirm_password'];
		if($password==$temppassword)
			return true;
		else
			return false;
	
	}

}
