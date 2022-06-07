<?php

class UsersController extends AppController {

    public $uses = array('User');
    public $components = array('Session', 'Paginator');

    public function beforeFilter() {
        parent::beforeFilter();
		$this->Auth->allow('login','forgot_password','reset_password');
    }

    /**
     * Change the password of the user by the admin
     * @param string $id
     * @return null
     */
    function admin_change_password() {

        $this->layout = LAYOUT_ADMIN;

        $id = base64_decode($this->params['id']);
        $is_valid = true;
        if('' == $id || !in_array($this->params['type'], array('customers', 'drivers'))){
            $is_valid = false;
        }else{
            $user_data = $this->User->Find('first', array(
                'fields' => array('User.first_name', 'User.last_name'),
                'conditions' => array('User.id' => $id)
            ));

            $this->set(compact('id', 'user_data'));
            if (empty($user_data)) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
        }

        $this->set('tab_open', $this->params['type']);
        if (!empty($this->request->data)) {

            $this->User->set($this->request->data);

            if ($this->User->validates()) {

                $new_password = Security::hash($this->request->data['User']['password'], 'md5');

                $this->User->updateAll(array('User.password' => "'" . $new_password . "'"), array('User.id' => $id));
                $this->Session->setFlash('Password has been updated successfully', 'success');
                $this->redirect(array('plugin' => false, 'controller' => $this->params['type'], 'action' => 'index', 'admin' => true));
            }
        }
    }
	
	
	
	public function login() {

        $this->layout = false;
        $this->autoRender = false;
        if ($this->request->is('Ajax')) {

            if (isset($this->request->data['username']) && isset($this->request->data['password'])) {
                $username = $this->request->data['username'];
                $password = Security::hash($this->data['password'], 'md5', false);
                $user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.email' => $username, 'User.password' => $password
                    )
                ));
                
                if (!empty($user)) {
					if(($user['User']['user_type'] == 'D') && $user['User']['driver_status'] == 'A'){
						$this->Auth->login($user['User']);
					}
					
					if(($user['User']['user_type'] == 'N') && $user['User']['customer_status'] == 'A'){
						$this->Auth->login($user['User']);
					}
                                        if(($user['User']['user_type'] == 'B') && ($user['User']['customer_status'] == 'A' || $user['User']['driver_status'] == 'A')){
						$this->Auth->login($user['User']);
					}
                }
				
                if ($this->Auth->login()) {
					
                    echo json_encode(array('success'=>'1','redirect'=>$this->Auth->loginRedirect)); die;
                } else {
                    echo json_encode(array('success'=>'0','message'=>'Invalid Username or Password')); die;
                }
            }
        }

        if ($this->Auth->loggedIn() || $this->Auth->login()) {
            return $this->redirect(array('controller' => 'home', 'action' => 'index', 'admin' => false));
        }
    }
	
	
	public function logout() {

        $session_id = $this->Session->id();//Get the id of current session
        $this->Auth->logout();
        $this->Session->destroy();//To destroy all session created by logged in user
        $this->Session->id($session_id);//To work logout setFlash session message after destroy session
        //$this->Session->setFlash('You have successfully logged out','success');
        $this->redirect(array('controller' => 'home', 'action' => 'index', 'admin' => false));
        //$this->redirect($this->Auth->logoutRedirect);
    }
	
	public function upload_photo(){
		
		$this->layout = false;
        $this->autoRender = false;
		
		App::import('Vendor', 'UploadHandler', array('file' => 'upload-file/UploadHandler.php'));
        $options = array(
            'upload_dir' => USER_PHOTO_PATH,        
			'upload_url' => '',
			'param_name' => 'eventbanner',
            'accept_file_types' => '/\.(gif|jpe?g|png)$/i' ,
			'image_versions' => array(
			
			'medium' => array(
                    'max_width' => 300,
                    'max_height' => 240,
					'upload_dir' => USER_PHOTO_PATH_LARGE,
                ),
                
                'thumbnail' => array(
                    
                    'upload_dir' => USER_PHOTO_PATH_THUMB,
                    
                    'max_width' => 150,
                    'max_height' => 120
                )
			
			)
           );

        $upload_handler = new UploadHandler($options);
		
	}
	
	public function save_photo(){
		
		$this->layout = false;
        $this->autoRender = false;
		 if ($this->request->is('Ajax')) {
			if (!empty($this->request->data)) {
				
				$this->request->data['User']['photo'] = $this->request->data['filename'];
				$this->Session->write('User.photo',$this->request->data['filename']);
				$this->User->updateAll(array('User.photo' => "'" . $this->request->data['filename'] . "'"), array('User.id' => $this->Session->read('User.id')));
			}
		 }	
		
	}
	
	
	public function forgot_password(){
		
		$this->layout = false;
        $this->autoRender = false;
		 if ($this->request->is('Ajax')) {
			if (!empty($this->request->data)) {
				
				$email = $this->request->data['email'];
				
				$user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.email' => $email
                    )
                ));
				if(empty($user)){
					
					echo json_encode(array('success'=>'0','message'=>'This email does not exists')); die;
					
				}
				else{
					$subject = 'PIDA : Reset password request';
					
					$generatedKey = sha1(mt_rand(10000, 99999) . time(). $email);
					
					$this->User->updateAll(array('User.reset_code' => "'".$generatedKey."'",'User.reset_request_sent_on' => strtotime('now')), array('User.email' => $email));
					
					$reset_link = $this->webroot.'home/reset_password?reset_code='.$generatedKey;
					$viewVars = array('reset_link' => $reset_link);
					$this->sendMail($email, $subject, 'forgot_password', 'default', $viewVars);
					echo json_encode(array('success'=>'1','message'=>'Your reset password request received. An email has been sent to this email address with reset password link.')); die;
				}
				
			}
		 }	
		
	}
	
	
	function reset_password() {

        $this->layout = false;
        $this->autoRender = false;

        
        if (!empty($this->request->data)) {

            $this->User->set($this->request->data);

            if ($this->User->validates()) {

				if($this->request->data['reset_code'] != ''){
					$new_password = Security::hash($this->request->data['password'], 'md5');

					$this->User->updateAll(array('User.password' => "'" . $new_password . "'",'User.reset_code' => NULL,'User.reset_request_sent_on' => NULL), array('User.reset_code' => $this->request->data['reset_code']));
					$this->Session->setFlash('Password has been updated successfully', 'success');
					$this->redirect(array('plugin' => false, 'controller' => 'home', 'action' => 'reset_thanks', 'admin' => false));
				}
				else{
					$this->Session->setFlash('Invalid request', 'success');
				}
            }
        }
    }
	
	

}
