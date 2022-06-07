<?php

class DriversController extends AppController {

    public $uses = array('User','Vehicle');
    public $components = array('Session', 'Paginator');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'drivers');
    }

    /**
     * To list drivers
     * @return mixed
     */
    function admin_index(){

        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;

        $order = 'User.created DESC';
        $conditions = array('User.user_type' => array('D', 'B'));

        if (!empty($this->request->data)) {

            if(isset($this->request->data['User']) && !empty($this->request->data['User'])) {
                $search_data = $this->request->data['User'];
                $this->Session->write('driver_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('driver_search')){
            $search = $this->Session->read('driver_search');
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['OR'] = array(
                    'CONCAT(User.first_name, " ", User.last_name) LIKE' => '%' . $search['search'] . '%',
                    'User.email LIKE' => '%' . $search['search'] . '%',
                    'User.phone LIKE' => '%' . $search['search'] . '%',
                    'DriverDetail.ssn LIKE' => '%' . $search['search'] . '%',
                    'DriverDetail.driving_license_no LIKE' => '%' . $search['search'] . '%'
                );
            }

            if(!empty($search['status'])){
                $conditions['User.driver_status'] = $search['status'];
            }

            if(!empty($search['registered_from'])){
                $conditions['User.created >='] = strtotime($search['registered_from']);
            }
            if(!empty($search['registered_till'])){
                $conditions['User.created <='] = strtotime($search['registered_till'] . ' 23:59:59');
            }
        }

        $this->User->bindModel(
            array(
                'hasOne' => array(
                    'DriverDetail' => array(
                        'className' => 'DriverDetail',
                        'foreignKey' => 'user_id'
                    )
                )
            ), false
        );

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
//pr($result_data); die;
        $this->set(compact('result_data', 'limit', 'order'));
    }

        /**
     * To list online drivers
     * @return mixed
     */
    function admin_online(){

        $this->set('tab_open', 'drivers_online');

        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;

        $order = 'User.created DESC';
        $conditions = array('User.user_type' => array('D', 'B'),'DriverDetail.is_online'=>'Y','User.driver_status'=>'A');

        if (!empty($this->request->data)) {

            if(isset($this->request->data['User']) && !empty($this->request->data['User'])) {
                $search_data = $this->request->data['User'];
                $this->Session->write('driver_online_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('driver_online_search')){
            $search = $this->Session->read('driver_online_search');
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['OR'] = array(
                    'CONCAT(User.first_name, " ", User.last_name) LIKE' => '%' . $search['search'] . '%',
                    'User.email LIKE' => '%' . $search['search'] . '%',
                    'User.phone LIKE' => '%' . $search['search'] . '%',
                    'DriverDetail.ssn LIKE' => '%' . $search['search'] . '%',
                    'DriverDetail.driving_license_no LIKE' => '%' . $search['search'] . '%'
                );
            }

            if(!empty($search['status'])){
                $conditions['User.driver_status'] = $search['status'];
            }

            if(!empty($search['registered_from'])){
                $conditions['User.created >='] = strtotime($search['registered_from']);
            }
            if(!empty($search['registered_till'])){
                $conditions['User.created <='] = strtotime($search['registered_till'] . ' 23:59:59');
            }
        }

        $this->User->bindModel(
            array(
                'hasOne' => array(
                    'DriverDetail' => array(
                        'className' => 'DriverDetail',
                        'foreignKey' => 'user_id'
                    )
                )
            ), false
        );

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
//pr($result_data); die;
        $this->set(compact('result_data', 'limit', 'order'));
    }

    /**
     * To add or edit driver detail
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

        $this->layout = LAYOUT_ADMIN;

        $this->User->bindModel(
            array(
                'hasOne' => array(
                    'DriverDetail' => array(
                        'className' => 'DriverDetail',
                        'foreignKey' => 'user_id'
                    )
                )
            )
        );

        $this->loadModel('DriverDetail');

        if (!empty($this->request->data)) {
            $this->User->set($this->request->data);
            $this->DriverDetail->set($this->request->data);
            if ($this->User->validates() && $this->DriverDetail->validates()) {

                if(isset($this->request->data['User']['password'])) {
                    $this->request->data['User']['password'] = Security::hash($this->request->data['User']['password'], 'md5');
                }
                if('' == $id){
                    $this->request->data['User']['user_type'] = 'D';
                    $this->request->data['User']['customer_status'] = 'I';
                }
                if ($this->User->save($this->request->data, $validate = false)) {

                    $last_id = $this->User->id;
                    $this->request->data['DriverDetail']['user_id'] = $last_id;
                    $this->DriverDetail->save($this->request->data, $validate = false);
                    $driver_detail_last_id = $this->DriverDetail->id;

                    if(isset($this->request->data['User']['user_photo']) && !empty($this->request->data['User']['user_photo'])){
                        $file_data = $this->request->data['User']['user_photo'];
                        if(0 === $file_data['error']){
                            $file_name = $file_data['name'];
                            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                            $file_name_final = 'user_photo_' . mt_rand(100, 999) . '_' . time() . '.' . $file_ext;

                            if (move_uploaded_file($file_data['tmp_name'], USER_PHOTO_PATH . $file_name_final)) {
                                $this->resize($file_name_final, 300, 240, USER_PHOTO_PATH, USER_PHOTO_PATH_LARGE);
                                $this->resize($file_name_final, 150, 120, USER_PHOTO_PATH, USER_PHOTO_PATH_THUMB);

                                // unlink(USER_PHOTO_PATH . $file_name_final);

                                //remove old file
                                if('' != $id && isset($this->request->data['User']['recent_photo']) && !empty($this->request->data['User']['recent_photo'])){
                                    @unlink(USER_PHOTO_PATH_LARGE . $this->request->data['User']['recent_photo']);
                                    @unlink(USER_PHOTO_PATH_THUMB . $this->request->data['User']['recent_photo']);
                                    @unlink(USER_PHOTO_PATH . $this->request->data['User']['recent_photo']);
                                }

                                //update with new file
                                $this->User->updateAll(array('User.photo' => "'" . $file_name_final . "'"), array('User.id' => $last_id));
                            }
                        }
                    }

                    if(isset($this->request->data['DriverDetail']['dl_doc']) && !empty($this->request->data['DriverDetail']['dl_doc'])){
                        $file_data = $this->request->data['DriverDetail']['dl_doc'];
                        if(0 === $file_data['error']){
                            $file_name = $file_data['name'];
                            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                            $file_name_final = 'dl_doc_' . mt_rand(100, 999) . '_' . time() . '.' . $file_ext;

                            if (move_uploaded_file($file_data['tmp_name'], DRIVER_DOC_PATH . $file_name_final)) {
                                $this->resize($file_name_final, 300, 240, DRIVER_DOC_PATH, DRIVER_DOC_PATH_LARGE);
                                $this->resize($file_name_final, 150, 120, DRIVER_DOC_PATH, DRIVER_DOC_PATH_THUMB);

                            //    unlink(DRIVER_DOC_PATH . $file_name_final);

                                //remove old file
                                if('' != $id && isset($this->request->data['DriverDetail']['recent_dl_doc']) && !empty($this->request->data['DriverDetail']['recent_dl_doc'])){
                                    @unlink(DRIVER_DOC_PATH_LARGE . $this->request->data['DriverDetail']['recent_dl_doc']);
                                    @unlink(DRIVER_DOC_PATH_THUMB . $this->request->data['DriverDetail']['recent_dl_doc']);
                                    @unlink(DRIVER_DOC_PATH . $this->request->data['DriverDetail']['recent_dl_doc']);
                                }

                                //update with new file
                                $this->DriverDetail->updateAll(array('DriverDetail.driving_license_doc' => "'" . $file_name_final . "'"), array('DriverDetail.id' => $driver_detail_last_id));
                            }
                        }
                    }

                    if('' == $id){
                        $this->Session->setFlash('Driver has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('Driver has been updated successfully', 'success');
                    }

                    $this->redirect(array('plugin' => false, 'controller' => 'drivers', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){
            $id = base64_decode($id);
            $result_data = $this->User->find('first', array('conditions' => array('User.id' => $id, 'User.user_type' => array('D', 'B'))));
            if(empty($result_data)){
                $this->Session->setFlash('Invalid Request !', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'drivers', 'action' => 'index', 'admin' => true));
            }

            if (empty($this->request->data)) {
                $this->request->data = $result_data;
            }
            $this->request->data['User']['photo'] = $result_data['User']['photo'];
            $this->request->data['DriverDetail']['driving_license_doc'] = $result_data['DriverDetail']['driving_license_doc'];
        }

        $this->set(compact('id'));
    }


    /**
     * Change the status of the driver
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
            $check_user_exists = $this->User->Find('first', array('fields' => array('User.first_name', 'User.last_name', 'User.email','User.register_password','User.is_verified'), 'conditions' => array('User.id' => $id)));
            if (empty($check_user_exists)) {
                $is_valid = false;
            }else{
                $name = ucfirst($check_user_exists['User']['first_name']) . ' ' . ucfirst($check_user_exists['User']['last_name']);
                $email = $check_user_exists['User']['email'];
                $registerPassword = base64_decode($check_user_exists['User']['register_password']);
            }
        }

        if($is_valid) {

            if($check_user_exists['User']['is_verified'] == 'N' && $status == 'A'){
                $viewVars = array('name' => $name, 'email' => $email , 'register_password' => $registerPassword);
                $subject = 'Pida: Driver Account Approved';
                $this->User->updateAll(array('User.driver_status' => "'" . $status . "'"), array('User.id' => $id));
                $this->sendMail($email, $subject, 'driver_account_approved', 'default', $viewVars);
				$this->updateDriverBankIdentificationDoc($id);
            }else{

                $this->User->updateAll(array('User.driver_status' => "'" . $status . "'"), array('User.id' => $id));
                $this->Vehicle->updateAll(array('Vehicle.status' => "'" . $status . "'"), array('Vehicle.user_id' => $id));

                if('A' == $status){
                    $viewVars = array('name' => $name, 'type' => 'active');
                    $subject = 'Pida: Profile Activation';
                }else{
                    $viewVars = array('name' => $name, 'type' => 'inactive');
                    $subject = 'Pida: Profile Deactivated';
                }

                $this->sendMail($email, $subject, 'status_update', 'default', $viewVars);
            }
            

            $this->Session->setFlash('Driver status has been changed successfully', 'success');
            $this->redirect(Router::url( $this->referer(), true ));

        }else{
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'drivers', 'action' => 'index', 'admin' => true));
        }
    }

    /**
     * Change the status of the driver to offline
     * @param string $id
     * @return null
     */
    public function admin_offline($id = '') {

        $this->loadModel('DriverDetail');

        $id = base64_decode($id);

        $is_valid = true;
        $name = $email = '';
        if('' == $id){
            $is_valid = false;
        }else{
            $check_user_exists = $this->User->Find('first', array('fields' => array('User.id'), 'conditions' => array('User.id' => $id)));
            if (empty($check_user_exists)) {
                $is_valid = false;
            }
        }

        if($is_valid) {

            $offlineStatus = 'N';
            $this->DriverDetail->updateAll(array('DriverDetail.is_online' => "'" . $offlineStatus . "'"), array('DriverDetail.user_id' => $id));

            $this->Session->setFlash('Driver is offline now', 'success');
            $this->redirect(Router::url( $this->referer(), true ));

        }else{
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'drivers', 'action' => 'index', 'admin' => true));
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
            'BankDetail' => array(
                'className' => 'BankDetail',
                'foreignKey' => 'user_id'
            ),    
			'DriverDetail' => array(
			    'className' => 'DriverDetail',
			    'foreignKey' => 'user_id'
			),
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
		    'hasMany' => array(
			'DriverQuestion' => array(
			    'className' => 'DriverQuestion',
			    'foreignKey' => 'driver_id'
			)
		    )
		)
	);

	$this->loadModel('Vehicle');
	$this->loadModel('DriverDetail');
    $this->loadModel('BankDetail');

	if ('' != $id) {
	    $id = base64_decode($id);
	    $result_data = $this->User->find('first', array('conditions' => array('User.id' => $id, 'User.user_type' => array('D', 'B'))));
	    $result_data['Vehicle'] = $this->Vehicle->getDriverVehicles(array('user_id'=>$id));
	    if (empty($result_data)) {
		$this->Session->setFlash('Invalid Request !', 'error');
		$this->redirect(array('plugin' => false, 'controller' => 'drivers', 'action' => 'index', 'admin' => true));
	    }
     //pr($result_data);die;
	    if (empty($this->request->data)) {
		$this->request->data = $result_data;
	    }
//	    $this->request->data['User']['photo'] = $result_data['User']['photo'];
//	    $this->request->data['DriverDetail']['driving_license_doc'] = $result_data['DriverDetail']['driving_license_doc'];
	}

	$this->set(compact('id'));
    }
	
	
	public function admin_delete() {
        $item_id = $this->params->query['id'];
		
        if (!$item_id) {
            $this->Session->setFlash('Invalid Request, Driver id not found', 'error');
            echo json_encode(array('succ' => 0, 'msg' => 'Invalid Request, Driver id not found'));
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
                   // if (file_exists(USER_PIC_UPLOAD_IMAGE_PATH.$userData['User']['photo'])) {
                        @unlink(USER_PHOTO_URL .  $userData['User']['photo']);
						@unlink(USER_PHOTO_URL_LARGE .  $userData['User']['photo']);
						@unlink(USER_PHOTO_URL_THUMB .  $userData['User']['photo']);
						
						
						$this->loadModel('DriverDetail');
					    $this->DriverDetail->deleteAll(array('DriverDetail.user_id' =>$userData['User']['id']), false);
						
						$this->loadModel('BankDetail');
					    $this->BankDetail->deleteAll(array('BankDetail.user_id' => $userData['User']['id']), false);
						
						$this->loadModel('Vehicle');
						$this->Vehicle->deleteAll(array('Vehicle.user_id' => $userData['User']['id']), false);
						
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
						  
                  //  }
                }
                $this->Session->setFlash('Driver deleted successfully', 'success');
                echo json_encode(array('succ' => 1, 'msg' => 'User deleted successfully'));
                die;
            } else {
                $this->Session->setFlash('Driver couldn\'t be deleted, please try again later', 'error');				
                echo json_encode(array('succ' => 0, 'msg' => 'User couldn\'t be deleted, please try again later'));
                die;
            }
        }
        exit;
    }
	
	private function updateDriverBankIdentificationDoc($driver_id){
		$this->loadModel('DriverDetail');
		//fetching driver's documents
		$driver_details = $this->getDriverDetails($driver_id);
		$driver_documents = $this->DriverDetail->find("first", array("conditions"=>array("DriverDetail.user_id"=>$driver_id)));
		if(!empty($driver_documents)){
			//fetching driver's bank account details first
			$this->loadModel("BankDetail");
			$bank_details = $this->BankDetail->find("first", array("conditions"=>array("user_id"=>$driver_id)));
			if(!empty($bank_details) && !empty($bank_details["BankDetail"]["stripe_bank_account_id"])){
				//including stripe library
				require_once STRIPE_AUTOLOAD;
				\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
				//we will check if driver has an account on stripe already?
				$stripe_driver_id = $driver_details["User"]["stripe_driver_id"];
				if(empty($stripe_driver_id)){
					try{
						$account = \Stripe\Account::create(array(
									  "type" => "custom",
										"country" => "US",
										"email" => $driver_details["User"]["email"],
										"legal_entity" => array(
											'address' => array(
												'city' => '',
												'country' => strtoupper($driver_details["Country"]["country_code"]),
												"line1" => '',
												"line2" => '',
												"postal_code" => '',
												"state" => ''
											),
											'first_name' => $driver_details["User"]["first_name"],
											'last_name' => $driver_details["User"]["last_name"],
											'phone_number' => $driver_details["User"]["phone"]
										),
										'tos_acceptance' => array(
											'date' => time(),
											'ip' => $_SERVER['REMOTE_ADDR']
										)
									));
						
						$stripe_res = $account->__toArray(true);
						
						$text = print_r($stripe_res, true);
						
						$this->saveStripeResponse($text, "SUCCESS", $user_data["user_id"]);
						$stripe_driver_id = $stripe_res["id"];
						
						$this->User->updateAll(array("stripe_driver_id"=>"'$stripe_driver_id'"), array("id"=>$driver_id));
					}
					catch(\Stripe\Error\Base $e){
						$text = print_r($e->getMessage()."\r\n", true);
						$this->saveStripeResponse($text, "FAILURE", $user_data["user_id"]);
						return false;
					}
				}
				//now we have driver's account, we can add driver details
				//!=$driver_documents["DriverDetail"]["driving_license_doc"]
				if(empty($bank_details["BankDetail"]["driving_license_doc"])){
					try{
						//retrieving driver's account details
						$account = \Stripe\Account::retrieve($stripe_driver_id);
						//we need to update the verification doc
						try{
							
							$fp = fopen(DRIVER_DOC_PATH_LARGE.$driver_documents["DriverDetail"]["driving_license_doc"], 'r');
							$stripe_document_res = \Stripe\FileUpload::create(array(
							  'purpose' => 'identity_document',
							  'file' => $fp
							));
							
							$stripe_document_res_arr = $stripe_document_res->__toArray(true);
							$text = print_r($stripe_document_res_arr, true);
							$this->saveStripeResponse($text, "FILE_UPLOAD", $driver_documents["DriverDetail"]["user_id"]);
							
							$this->BankDetail->updateAll(array("stripe_document_id"=>"'".$stripe_document_res_arr["id"]."'", "driving_license_doc"=>"'".$driver_documents["DriverDetail"]["driving_license_doc"]."'", "driving_license_no"=>"'".$driver_documents["DriverDetail"]["driving_license_no"]."'"), array("user_id"=>$driver_id));
							
							//we got the document, so we'll pass it
							$account->legal_entity->verification = array(
								"document" => $stripe_document_res_arr["id"]
							);
							$account->save();
						}
						catch(\Stripe\Error\Base $e){
							$text = print_r($e->getMessage()."\r\n", true);
							$this->saveStripeResponse($text, "FAILURE", $fetchDriverDetails["id"]);
							return UNABLE_TO_PROCEED2;
						}
					}
					catch(\Stripe\Error\Base $e){
						$text = print_r($e->getMessage()."\r\n", true);
						$this->saveStripeResponse($text, "FAILURE", $fetchDriverDetails["id"]);
						return UNABLE_TO_PROCEED2;
					}	
				}
			}
		}
	}
	
	private function getDriverDetails($driver_id, $langCode="en"){
		$this->loadModel("User");
		$this->User->bindModel(
			array(
				'hasOne' => array(
				'BankDetail' => array(
					'className' => 'BankDetail',
					'foreignKey' => 'user_id'
				),    
				'DriverDetail' => array(
					'className' => 'DriverDetail',
					'foreignKey' => 'user_id'
				),
				'Country' => array(
					'className' => 'Country',
					'foreignKey' => false,
					'conditions' => array("User.country_id = Country.id"),
				),
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
				'hasMany' => array(
				'DriverQuestion' => array(
					'className' => 'DriverQuestion',
					'foreignKey' => 'driver_id'
				)
				)
			)
		);
		return $this->User->findById($driver_id);
	}
    //Update Approval 
    public function admin_update_approval() {
        $this->loadModel('DriverDetail');
        $d_id = $_GET['id'];
        $user_data = $this->DriverDetail->find('first', array(
            'conditions' => array('DriverDetail.user_id' => $d_id)
        ));
        if($user_data['DriverDetail']['approve'] == 'N'){
            $approveStatus = 'Y';
            $this->DriverDetail->updateAll(array('DriverDetail.approve' => "'" . $approveStatus . "'"), array('DriverDetail.user_id' => $d_id));         
        }else{
            $approveStatus = 'N';
            $this->DriverDetail->updateAll(array('DriverDetail.approve' => "'" . $approveStatus . "'"), array('DriverDetail.user_id' => $d_id)); 
        }
        $this->Session->setFlash('Driver deleted successfully', 'success');
                echo json_encode(array('succ' => 1, 'msg' => 'User deleted successfully'));
                die;
        
    }
}
