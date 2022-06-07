<?php

class PrivacyPolicyController extends AppController {

    public $uses = array('User');
    public $components = array('Session', 'Paginator', 'Auth' => array(
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'email')
                )
            )
        )
    );
	
	
	 public $reg_from = array(
		'D' => 'Day',
		'W' => 'Weekly',	
		'M' => 'Monthly',
    );
	
	

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('login','index');
        if($this->Auth->user('user_type') <> 'admin')
            $this->Auth->logout();
    }

    

	
	public function index() {
		$this->layout = 'Policy';

        
    }

   

}
