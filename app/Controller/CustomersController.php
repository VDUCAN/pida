<?php

class CustomersController extends AppController {

    public $uses = array('User');
    public $components = array('Session', 'Paginator');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'customers');
    }

    /**
     * To list customers
     * @return mixed
     */
    function admin_index(){
        
        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;

        $order = 'User.created DESC';
        $conditions = array('User.user_type' => array('N', 'B'));

        if (!empty($this->request->data)) {

            if(isset($this->request->data['User']) && !empty($this->request->data['User'])) {
                $search_data = $this->request->data['User'];
                $this->Session->write('customer_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('customer_search')){
            $search = $this->Session->read('customer_search');
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['OR'] = array(
                    'CONCAT(User.first_name, " ", User.last_name) LIKE' => '%' . $search['search'] . '%',
                    'User.email LIKE' => '%' . $search['search'] . '%',
                    'User.phone LIKE' => '%' . $search['search'] . '%'
                );
            }

            if(!empty($search['status'])){
                $conditions['User.customer_status'] = $search['status'];
            }

            if(!empty($search['registered_from'])){
                $conditions['User.created >='] = strtotime($search['registered_from']);
            }
            if(!empty($search['registered_till'])){
                $conditions['User.created <='] = strtotime($search['registered_till'] . ' 23:59:59');
            }
        }

        $query = array(
            'conditions' => $conditions,
            'order' => $order
        );
        if('all' == $limit){
            $result_data = $this->User->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $result_data = $this->paginate();
        }

        $this->set(compact('result_data', 'limit', 'order'));
    }

    /**
     * To add or edit customer detail
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

        $this->layout = LAYOUT_ADMIN;

        if (!empty($this->request->data)) {
            $this->User->set($this->request->data);
            if ($this->User->validates()) {

                if(isset($this->request->data['User']['password'])) {
                    $this->request->data['User']['password'] = Security::hash($this->request->data['User']['password'], 'md5');
                }
                if('' == $id){
                    $this->request->data['User']['user_type'] = 'N';
                    $this->request->data['User']['driver_status'] = 'I';
                }
                if ($this->User->save($this->request->data, $validate = false)) {

                    if(isset($this->request->data['User']['user_photo']) && !empty($this->request->data['User']['user_photo'])){
                        $file_data = $this->request->data['User']['user_photo'];
                        if(0 === $file_data['error']){
                            $last_id = $this->User->id;
                            $file_name = $file_data['name'];
                            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                            $file_name_final = 'user_photo_' . mt_rand(100, 999) . '_' . time() . '.' . $file_ext;

                            if (move_uploaded_file($file_data['tmp_name'], USER_PHOTO_PATH . $file_name_final)) {
                                $this->resize($file_name_final, 300, 240, USER_PHOTO_PATH, USER_PHOTO_PATH_LARGE);
                                $this->resize($file_name_final, 150, 120, USER_PHOTO_PATH, USER_PHOTO_PATH_THUMB);

                                unlink(USER_PHOTO_PATH . $file_name_final);

                                //remove old file
                                if('' != $id && isset($this->request->data['User']['recent_photo']) && !empty($this->request->data['User']['recent_photo'])){
                                    unlink(USER_PHOTO_PATH_LARGE . $this->request->data['User']['recent_photo']);
                                    unlink(USER_PHOTO_PATH_THUMB . $this->request->data['User']['recent_photo']);
                                }

                                //update with new file
                                $this->User->updateAll(array('User.photo' => "'" . $file_name_final . "'"), array('User.id' => $last_id));
                            }
                        }
                    }

                    if('' == $id){
                        $this->Session->setFlash('Customer has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('Customer has been updated successfully', 'success');
                    }

                    $this->redirect(array('plugin' => false, 'controller' => 'customers', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){
            $id = base64_decode($id);
            $result_data = $this->User->find('first', array('conditions' => array('User.id' => $id, 'User.user_type' => array('N', 'B'))));
            if(empty($result_data)){
                $this->Session->setFlash('Invalid Request !', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'customers', 'action' => 'index', 'admin' => true));
            }

            if (empty($this->request->data)) {
                $this->request->data = $result_data;
            }
            $this->request->data['User']['photo'] = $result_data['User']['photo'];
        }

        $this->set(compact('id'));
    }


    /**
     * Change the status of the customer
     * @param string $id
     * @param string $status
     * @return null
     */
    public function admin_status($id = '', $status = '') {

        $id = base64_decode($id);

        $is_valid = true;
        $name = $email = '';
        if('' == $id || '' == $status){
            $is_valid = false;
        }else{
            $check_user_exists = $this->User->Find('first', array('fields' => array('User.first_name', 'User.last_name', 'User.email'), 'conditions' => array('User.id' => $id)));
            if (empty($check_user_exists)) {
                $is_valid = false;
            }else{
                $name = ucfirst($check_user_exists['User']['first_name']) . ' ' . ucfirst($check_user_exists['User']['last_name']);
                $email = $check_user_exists['User']['email'];
            }
        }

        if($is_valid) {

            $this->User->updateAll(array('User.customer_status' => "'" . $status . "'"), array('User.id' => $id));

            if('A' == $status){
                $viewVars = array('name' => $name, 'type' => 'active');
                $subject = 'Pida: Profile Activation';
            }else{
                $viewVars = array('name' => $name, 'type' => 'inactive');
                $subject = 'Pida: Profile Deactivated';
            }

            $this->sendMail($email, $subject, 'status_update', 'default', $viewVars);

            $this->Session->setFlash('Customer status has been changed successfully', 'success');
            $this->redirect(Router::url( $this->referer(), true ));

        }else{
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'customers', 'action' => 'index', 'admin' => true));
        }

    }
	
	
	    
    /**
     * To view driver detail
     * @param string $id
     * @return mixed
     */
    function admin_view() { 

	$id = $this->params['named']['id'];
	
	$langCode = DEFAULT_LANGUAGE;

	$this->layout = LAYOUT_ADMIN;

	$this->User->bindModel(
		array(
		    'hasOne' => array(
				
				'CountryLocale' => array(
					'className' => 'CountryLocale',
					'foreignKey' => false,
					'conditions' => array("User.country_id = CountryLocale.country_id",'CountryLocale.lang_code' => $langCode),
				),
				'StateLocale' => array(
					'className' => 'StateLocale',
					'foreignKey' => false,
					'conditions' => array("User.state_id = StateLocale.state_id",'StateLocale.lang_code' => $langCode),
				),
				'CityLocale' => array(
					'className' => 'CityLocale',
					'foreignKey' => false,
					'conditions' => array("User.city_id = CityLocale.city_id",'CityLocale.lang_code' => $langCode),
				),
		    ),
		    
		)
	);

	$this->loadModel('Vehicle');
	$this->loadModel('DriverDetail');

	if ('' != $id) {
		
			$id = base64_decode($id);
			
			$result_data = $this->User->find('first', array('conditions' => array('User.id' => $id, 'User.user_type' => array('N', 'B'))));
	   
	    if (empty($result_data)) {
			$this->Session->setFlash('Invalid Request !', 'error');
			$this->redirect(array('plugin' => false, 'controller' => 'drivers', 'action' => 'index', 'admin' => true));
	    }
			//pr($result_data);die;
	    if (empty($this->request->data)) {
			$this->request->data = $result_data;
	    }

		

	}

	$this->set(compact('id'));
    }
	
	
	
	function admin_delete() {
        $item_id = $this->params->query['id'];		
        if (!$item_id) {
            $this->Session->setFlash('Invalid Request, User id not found', 'error');
            echo json_encode(array('succ' => 0, 'msg' => 'Invalid Request, User id not found'));
            die;
        } else {

            // fetch order's of user
            $userData = $this->User->find('first', array(
                'conditions' => array(
                    'User.id' => $item_id
                ),
                'fields' => array(
                    'User.id', 'User.photo'
                ),
                'recursive' => -1
            ));

            if (!empty($userData)) {  //echo $userData['User']['id'];die;

                if ($this->User->delete($userData['User']['id'])) {
                  
                        @unlink(USER_PHOTO_URL.$userData['User']['photo']);
						@unlink(USER_PHOTO_URL_LARGE.$userData['User']['photo']);
						@unlink(USER_PHOTO_URL_THUMB.$userData['User']['photo']);
						
						
						$this->loadModel('DriverDetail');
					    $this->DriverDetail->deleteAll(array('DriverDetail.user_id' =>$userData['User']['id']), false);
						
						$this->loadModel('BankDetail');
					    $this->BankDetail->deleteAll(array('BankDetail.user_id' => $userData['User']['id']), false);
						
						///Get and delete record of booking table
						$this->loadModel('Booking');
						$userData = $this->Booking->find('list', array('conditions' => array('Booking.driver_id' =>$userData['User']['id']),'fields' => array('Booking.id'), 'recursive' => -1));
					
						
						if($userData){
							
							$this->Booking->deleteAll(array('Booking.id' => $userData), false);
							
							$this->loadModel('BookingLocation');
							$this->BookingLocation->deleteAll(array('BookingLocation.booking_id' => $userData), false);
							
							$this->loadModel('BookingDeliveryType');
							$this->BookingDeliveryType->deleteAll(array('BookingDeliveryType.booking_id'=>$userData),false);							
							
							$this->loadModel('BookingCargoType');
							$this->BookingCargoType->deleteAll(array('BookingCargoType.booking_id'=>$userData),false);				
							$this->loadModel('BookingRequest');
							$this->BookingRequest->deleteAll(array('BookingRequest.booking_id' => $userData), false);
							
							$this->loadModel('BookingRequestTemp');
							$this->BookingRequestTemp->deleteAll(array('BookingRequestTemp.booking_id' => $userData), false);
							
							$this->loadModel('Transaction');
							$this->Transaction->deleteAll(array('Transaction.booking_id' => $userData), false);						
							
						}
						  
                  
                }
                $this->Session->setFlash('Customer deleted successfully', 'success');
                echo json_encode(array('succ' => 1, 'msg' => 'User deleted successfully'));
                die;
            } else {
                $this->Session->setFlash('Customer couldn\'t be deleted, please try again later', 'error');				
                echo json_encode(array('succ' => 0, 'msg' => 'User couldn\'t be deleted, please try again later'));
                die;
            }
        }
        exit;
    }
	

}
