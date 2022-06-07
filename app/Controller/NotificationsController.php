<?php

/**
 * Class CargoTypesController
 */
class NotificationsController  extends AppController {

    public $uses = array('Notification');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'notifications');
    }

	
	function riders_list($user_ids = '') {
	//echo $user_ids;

		$riders_list = array();
		if ($user_ids) {
			$user_ids = explode(",", $user_ids);
			$riders_list = $this->User->find('list', array(
			'conditions' => array(
				'User.id' => $user_ids,
			),
			'fields' => array(
				'User.id', 'User.email'
			)
			));
		}

		return $riders_list;
    }
	
	
    public function admin_rider() {
	$this->set('tab_open_inner', 'Notifications_send');
	$this->loadModel('Country');
	$this->set('title_for_layout', 'Send Notifications');
	$this->loadModel('User');
	$users_list = $this->User->find('all', array(
	    'conditions' => array(
		'User.user_type' => 'N',
	   
	    ),
	    'fields' => array(
		'User.id', 'User.email', 'User.first_name', 'User.last_name'
	    )
	));
	$this->set('users_list', $users_list);
	if ($this->request->is('post')) {
	   // pr($this->data); exit;
	    $users =isset($this->data['Notification']['Users']) &&  $this->data['Notification']['Users']? $this->data['Notification']['Users']:array();

	    if (empty($users)) {
		$this->Session->setFlash('Please select atleast one rider.', 'error');
		$this->redirect(array('plugin' => false, 'controller' => 'Notifications', 'action' => 'admin_rider'));
	    }
	    $users_ids = implode(",", $users);

	 

	    if (!empty($users)) {

		$users_data = $this->User->find('all', array(
		    'conditions' => array(
				'User.id' => $users,
		    ),
		    'fields' => array(
				'User.id', 'User.email', 'User.first_name', 'User.last_name'
		    )
		));

		foreach ($users_data as $dt) {
			//###### Send mail to users #######
			//pr($dt); exit;
			
			$subject = $this->data['Notification']['subject'];
			$message = $this->data['Notification']['message'];
			$this->SendNotificationMail($dt['User']['email'],$dt['User']['first_name'],$dt['User']['last_name'],$subject,$message);
		
		}

		// save all notification to BD
		$data = array(
			'users_id'=>$users_ids,
			'message'=>$this->data['Notification']['message'],
			'subject'=>$this->data['Notification']['subject'],
			'usertype'=>'N'
			);
		$this->loadModel('Notification');	
		$this->Notification->save($data,false);
		//notification

		$this->Session->setFlash('Notification has been sent successfully', 'success');
		$this->redirect(array('plugin' => false, 'controller' => 'Notifications', 'action' => 'admin_rider'));
	    } else {
		$this->Session->setFlash('Notification has been sent successfully', 'success');
		$this->redirect(array('plugin' => false, 'controller' => 'Notifications', 'action' => 'admin_rider'));
	    }
	  }
    }
	
	public function SendNotificationMail($email,$first_name,$last_name,$subject,$message){		
		
		
		$UserData = array();
		$UserData['first_name']    = $first_name;
		$UserData['email']         = $email;
		$UserData['last_name']	   = $last_name;
		$UserData['message']	   = $message;
		
		App::uses("CakeEmail", "Network/Email");
        $Email = new CakeEmail('sendgrid');
		$Email->from('info@pida.com')
		->to($email)
		->subject($subject)
		->template("notification")
		->emailFormat("html")
		->viewVars(array('UserData' => $UserData))
		->send();
		return true;	
		
			
	}
	

    public function admin_driver() {
       
	$this->set('tab_open_inner', 'Notifications_send');
	$this->loadModel('Country');
	$this->set('title_for_layout', 'Send Notifications');
	$this->loadModel('User');
	$users_list = $this->User->find('all', array(
	    'conditions' => array(
		'User.user_type' => 'D',
	   
	    ),
	    'fields' => array(
		'User.id', 'User.email', 'User.first_name', 'User.last_name'
	    )
	));
	$this->set('users_list', $users_list);
	if ($this->request->is('post')) {
	   // pr($this->data); exit;
	    $users =isset($this->data['Notification']['Users']) &&  $this->data['Notification']['Users']? $this->data['Notification']['Users']:array();

	    if (empty($users)) {
		$this->Session->setFlash('Plese select atleast one rider.', 'error');
		$this->redirect(array('plugin' => false, 'controller' => 'Notifications', 'action' => 'admin_driver'));
	    }
	    $users_ids = implode(",", $users);

	 

	    if (!empty($users)) {

		$users_data = $this->User->find('all', array(
		    'conditions' => array(
				'User.id' => $users,
		    ),
		    'fields' => array(
				'User.id', 'User.email', 'User.first_name', 'User.last_name'
		    )
		));

		foreach ($users_data as $dt) {
			//###### Send mail to users #######
			//pr($dt); exit;
			
			$subject = $this->data['Notification']['subject'];
			$message = $this->data['Notification']['message'];
			$this->SendNotificationMail($dt['User']['email'],$dt['User']['first_name'],$dt['User']['last_name'],$subject,$message);
		
		}

		// save all notification to BD
		$data = array(
			'users_id'=>$users_ids,
			'message'=>$this->data['Notification']['message'],
			'subject'=>$this->data['Notification']['subject'],
			'usertype'=>'D'
			);
		$this->Notification->save($data);

		$this->Session->setFlash('Notification has been sent successfully', 'success');
		$this->redirect(array('plugin' => false, 'controller' => 'Notifications', 'action' => 'admin_driver'));
	    } else {
		$this->Session->setFlash('Notification has been sent successfully', 'success');
		$this->redirect(array('plugin' => false, 'controller' => 'Notifications', 'action' => 'admin_driver'));
	    }
	  }
    }



}
