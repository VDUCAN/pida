<?php

/**
 * Class BookingsController
 */
class HomeController extends AppController {

    public $uses = array('Page', 'PageLocale', 'User','State', 'StateLocale', 'Language', 'CountryLocale','Booking','Driver','Transaction','ContactUs');
	
    public $components = array('Session','Paginator');
	
	

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index','page','customer_signup','driver_registration','thankyou','reset_password','reset_thanks','contact_us');
    }

    /**
     * admin_index For listing of bookings
     * @return mixed
     */
    public function index() {
        $this->layout = 'default';
		$about = $this->PageLocale->find('first', array('fields' => array('body','name'), 'conditions' => array('page_id' => '1','lang_code'=>'en')));
		$about1 = $this->PageLocale->find('first', array('fields' => array('body','name'), 'conditions' => array('page_id' => '4','lang_code'=>'en')));
		$about2 = $this->PageLocale->find('first', array('fields' => array('body','name'), 'conditions' => array('page_id' => '5','lang_code'=>'en')));
		$about3 = $this->PageLocale->find('first', array('fields' => array('body','name'), 'conditions' => array('page_id' => '6','lang_code'=>'en')));
		$download_app_section = $this->PageLocale->find('first', array('fields' => array('body','name'), 'conditions' => array('page_id' => '7','lang_code'=>'en')));
		$service1 = $this->PageLocale->find('first', array('fields' => array('body','name'), 'conditions' => array('page_id' => '8','lang_code'=>'en')));
		$service2 = $this->PageLocale->find('first', array('fields' => array('body','name'), 'conditions' => array('page_id' => '9','lang_code'=>'en')));
		$service3 = $this->PageLocale->find('first', array('fields' => array('body','name'), 'conditions' => array('page_id' => '10','lang_code'=>'en')));
		$service4 = $this->PageLocale->find('first', array('fields' => array('body','name'), 'conditions' => array('page_id' => '11','lang_code'=>'en')));
		$service5 = $this->PageLocale->find('first', array('fields' => array('body','name'), 'conditions' => array('page_id' => '12','lang_code'=>'en')));
		
		$this->set(compact('about','about1','about2','about3','download_app_section','service1','service2','service3','service4','service5'));
    }
	
	
	
	 public function page() {
        $this->layout = 'default';
		
		
		if(isset($this->request->params['id'])){
			$pageContent = $this->PageLocale->find('first', array('fields' => array('body','name'), 'conditions' => array('page_id' => $this->request->params['id'],'lang_code'=>'en')));
			
			
			$this->set(compact('pageContent'));
		}
    }
	
	
	public function thankyou() {
        $this->layout = 'default';
		
    }
	
	public function reset_thanks() {
        $this->layout = 'default';
		
    }
	
	
	
		
	/**
	Noraml User signup from frontend
	**/
	
	
	public function customer_signup() {

     	$this->layout = false;
        $this->autoRender = false;
		
        $result_data = array();
        if ($this->request->is('Ajax')) {
			if (!empty($this->request->data)) {
				
				$userDetail['User']['first_name'] = $this->request->data['first_name'];
				$userDetail['User']['last_name'] = $this->request->data['last_name'];
				$userDetail['User']['email'] = $this->request->data['email'];
				$userDetail['User']['phone'] = $this->request->data['phone'];
				$userDetail['User']['password'] = $this->request->data['password'];
				
				
				
				$this->User->set($userDetail);
				if ($this->User->validates()) {

					if(isset($userDetail['User']['password'])) {
						$userDetail['User']['password'] = Security::hash($userDetail['User']['password'], 'md5');
					}
					
					if($this->request->data['user_type'] == 'N'){
						$userDetail['User']['user_type'] = 'N';
						$userDetail['User']['driver_status'] = 'I';
						$userDetail['User']['customer_status'] = 'A';
					}	
					
					if($this->request->data['user_type'] == 'D'){
						$userDetail['User']['user_type'] = 'D';
						$userDetail['User']['customer_status'] = 'I';
						$userDetail['User']['driver_status'] = 'A';
					}	
					
					if ($this->User->save($userDetail,false)) {
						$this->Session->write('Driver.SignID',$this->User->id);
						$result_data = array('success'=>'1','message'=>'Thanks for registration. you account created successfully.');
					}
				}
				else{
					
					
					$result_data = array('success'=>'0','message'=>$this->User->validationErrors);
					
				}
			}
        }

        echo json_encode($result_data); die;
    }
	
	
	
	
	public function driver_registration() {

     	$this->layout = 'default';
        $user_id = $this->Session->read('Driver.SignID');
		$languages = $this->Language->find('list', array('fields' => array('lang_code', 'language'), 'conditions' => array('status' => 'A')));
		
        if (!empty($this->request->data)) {
			
			$dob = '"'.date('Y-m-d',strtotime($this->request->data['dob'])).'"';
            $this->User->updateAll(array('User.country_id' => $this->request->data['country'], 'User.state_id' => $this->request->data['state'], 'User.dob' => $dob), array('User.id' => $user_id));
			
			$this->loadModel('Vehicle');
			$this->Vehicle->validate = array();
			
			$Vehicle['Vehicle']['user_id'] = $user_id;
			$Vehicle['Vehicle']['vehicle_type_id'] = $this->request->data['vehicle_type'];
			$Vehicle['Vehicle']['make_id'] = $this->request->data['vehicle_make'];
			$Vehicle['Vehicle']['model_id'] = $this->request->data['vehicle_model'];
			$Vehicle['Vehicle']['plate_no'] = $this->request->data['plate_number'];
			$Vehicle['Vehicle']['make_year'] = $this->request->data['make_year'];
			$Vehicle['Vehicle']['color'] = $this->request->data['vehicle_color'];
			
		
			
			$this->Vehicle->save($Vehicle);
			
			$last_id = $this->Vehicle->id;
			
			if(isset($_FILES['registration_doc']) && !empty($_FILES['registration_doc'])){
				
				$file_data = $_FILES['registration_doc'];
				
				if(0 === $file_data['error']){
					$file_name = $file_data['name'];
					$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
					$file_name_final = 'registration_doc_' . mt_rand(100, 999) . '_' . time() . '.' . $file_ext;

					if (move_uploaded_file($file_data['tmp_name'], VEHICLE_DOC_PATH . $file_name_final)) {
						$this->resize($file_name_final, 300, 240, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_LARGE);
						$this->resize($file_name_final, 150, 120, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_THUMB);

						unlink(VEHICLE_DOC_PATH . $file_name_final);

						
						//update with new file
						$this->Vehicle->updateAll(array('Vehicle.registration_doc' => "'" . $file_name_final . "'", 'Vehicle.is_registration_doc_approved' => "'P'", 'Vehicle.status' => "'I'"), array('Vehicle.id' => $last_id));
					}
				}
            }

			if(isset($_FILES['insurance_doc']) && !empty($_FILES['insurance_doc'])){
				$file_data = $_FILES['insurance_doc'];
				if(0 === $file_data['error']){
					$file_name = $file_data['name'];
					$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
					$file_name_final = 'insurance_policy_doc_' . mt_rand(100, 999) . '_' . time() . '.' . $file_ext;

					if (move_uploaded_file($file_data['tmp_name'], VEHICLE_DOC_PATH . $file_name_final)) {
						$this->resize($file_name_final, 300, 240, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_LARGE);
						$this->resize($file_name_final, 150, 120, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_THUMB);

						unlink(VEHICLE_DOC_PATH . $file_name_final);

						

						//update with new file
						$this->Vehicle->updateAll(array('Vehicle.insurance_policy_doc' => "'" . $file_name_final . "'", 'Vehicle.is_insurance_policy_doc_approved' => "'P'", 'Vehicle.status' => "'I'"), array('Vehicle.id' => $last_id));
					}
				}
			}
			
			
			
			
						
			$this->loadModel('DriverDetail');
			
			$this->DriverDetail->validate = array();
			
			$driverDetail['DriverDetail']['ssn'] = $this->request->data['ssn'];
			$driverDetail['DriverDetail']['driving_license_no'] = $this->request->data['license_number'];
			$driverDetail['DriverDetail']['user_id'] = $user_id;
			$driverDetail['DriverDetail']['vehicle_id'] = $last_id;
			
			$this->DriverDetail->save($driverDetail);
			
			$driver_detail_last_id = $this->DriverDetail->id;
			
			if(isset($_FILES['license_doc']) && !empty($_FILES['license_doc'])){
				$file_data = $_FILES['license_doc'];
				if(0 === $file_data['error']){
					$file_name = $file_data['name'];
					$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
					$file_name_final = 'dl_doc_' . mt_rand(100, 999) . '_' . time() . '.' . $file_ext;

					if (move_uploaded_file($file_data['tmp_name'], DRIVER_DOC_PATH . $file_name_final)) {
						$this->resize($file_name_final, 300, 240, DRIVER_DOC_PATH, DRIVER_DOC_PATH_LARGE);
						$this->resize($file_name_final, 150, 120, DRIVER_DOC_PATH, DRIVER_DOC_PATH_THUMB);

						unlink(DRIVER_DOC_PATH . $file_name_final);

						

						//update with new file
						$this->DriverDetail->updateAll(array('DriverDetail.driving_license_doc' => "'" . $file_name_final . "'"), array('DriverDetail.id' => $driver_detail_last_id));
					}
				}
			}
			
			$this->loadModel('DriverQuestion');
			$this->DriverQuestion->validate = array();
			
			$DriverQuestion['DriverQuestion']['driver_id'] = $user_id;
			$DriverQuestion['DriverQuestion']['question'] = $this->request->data['question1'];
			$DriverQuestion['DriverQuestion']['answer'] = $this->request->data['answer1'];
			$DriverQuestion['DriverQuestion']['created'] = strtotime('now');
			$DriverQuestion['DriverQuestion']['modified'] = strtotime('now');
			
			$this->DriverQuestion->save($DriverQuestion);
			
			$DriverQuestion['DriverQuestion']['driver_id'] = $user_id;
			$DriverQuestion['DriverQuestion']['question'] = $this->request->data['question2'];
			$DriverQuestion['DriverQuestion']['answer'] = $this->request->data['answer2'];
			$DriverQuestion['DriverQuestion']['created'] = strtotime('now');
			$DriverQuestion['DriverQuestion']['modified'] = strtotime('now');
			$this->DriverQuestion->create();
			$this->DriverQuestion->save($DriverQuestion);
			
			$DriverQuestion['DriverQuestion']['driver_id'] = $user_id;
			$DriverQuestion['DriverQuestion']['question'] = $this->request->data['question3'];
			$DriverQuestion['DriverQuestion']['answer'] = $this->request->data['answer3'];
			$DriverQuestion['DriverQuestion']['created'] = strtotime('now');
			$DriverQuestion['DriverQuestion']['modified'] = strtotime('now');
			$this->DriverQuestion->create();
			$this->DriverQuestion->save($DriverQuestion);
			
			$this->Session->setFlash('Thanks for registration. your details will be verified soon.', 'success');
			
            $this->redirect(array('plugin' => false, 'controller' => 'home', 'action' => 'thankyou', 'admin' => false));
        }
		
		
		$countries = $this->CountryLocale->find('list',
            array(
                'fields' => array('CountryLocale.country_id', 'CountryLocale.name'),
                'conditions' => array('CountryLocale.lang_code' => 'en'),
                'order' => array('CountryLocale.name' => 'ASC')
            )
        );
		
		$this->loadModel('CategoryLocale');
        $categories = $this->CategoryLocale->find('list', array('fields' => array('CategoryLocale.category_id', 'CategoryLocale.name'), 'conditions' => array('CategoryLocale.lang_code' => 'en' /*'Category.status' => 'A'*/), 'order' => array('CategoryLocale.name' => 'ASC')));
		
		$this->loadModel('MakeLocale');
        $makes = $this->MakeLocale->find('list', array('fields' => array('MakeLocale.make_id', 'MakeLocale.name'), 'conditions' => array('MakeLocale.lang_code' => 'en' /*'Make.status' => 'A'*/), 'order' => array('MakeLocale.name' => 'ASC')));
		
        $this->set(compact('id', 'languages', 'countries','categories','makes'));

        
    }
	
	
	
	
	
	public function dashboard() {

     	$this->layout = 'default';
        $user_id = $this->Session->read('User.id');
		$limit = DEFAULT_PAGE_SIZE;
		
		$languages = $this->Language->find('list', array('fields' => array('lang_code', 'language'), 'conditions' => array('status' => 'A')));
		
		if (!empty($this->request->data)) {
			
			if($this->request->data['formtype'] == 'editprofile'){
				
				$this->request->data['User']['dob'] = date('Y-m-d',strtotime($this->request->data['User']['dob']));
				
				$this->User->set($this->request->data);

				if ($this->User->save($this->request->data,false)) {

				$this->Session->setFlash('Customer details has been updated successfully', 'success');
					
				}
			}	
			
			if($this->request->data['formtype'] == 'addvehicle'){
				$this->loadModel('Vehicle');
			
				$this->Vehicle->validate = array();
				$Vehicle['Vehicle']['user_id'] = $user_id;
				$Vehicle['Vehicle']['vehicle_type_id'] = $this->request->data['vehicle_type'];
				$Vehicle['Vehicle']['make_id'] = $this->request->data['vehicle_make'];
				$Vehicle['Vehicle']['model_id'] = $this->request->data['vehicle_model'];
				$Vehicle['Vehicle']['plate_no'] = $this->request->data['plate_number'];
				$Vehicle['Vehicle']['make_year'] = $this->request->data['make_year'];
				$Vehicle['Vehicle']['color'] = $this->request->data['vehicle_color'];
				
			
				
				$this->Vehicle->save($Vehicle,false);
				
				$last_id = $this->Vehicle->id;
				
				if(isset($_FILES['registration_doc']) && !empty($_FILES['registration_doc'])){
					
					$file_data = $_FILES['registration_doc'];
					
					if(0 === $file_data['error']){
						$file_name = $file_data['name'];
						$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
						$file_name_final = 'registration_doc_' . mt_rand(100, 999) . '_' . time() . '.' . $file_ext;

						if (move_uploaded_file($file_data['tmp_name'], VEHICLE_DOC_PATH . $file_name_final)) {
							$this->resize($file_name_final, 300, 240, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_LARGE);
							$this->resize($file_name_final, 150, 120, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_THUMB);

							unlink(VEHICLE_DOC_PATH . $file_name_final);

							
							//update with new file
							$this->Vehicle->updateAll(array('Vehicle.registration_doc' => "'" . $file_name_final . "'", 'Vehicle.is_registration_doc_approved' => "'P'", 'Vehicle.status' => "'I'"), array('Vehicle.id' => $last_id));
						}
					}
				}

				if(isset($_FILES['insurance_doc']) && !empty($_FILES['insurance_doc'])){
					$file_data = $_FILES['insurance_doc'];
					if(0 === $file_data['error']){
						$file_name = $file_data['name'];
						$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
						$file_name_final = 'insurance_policy_doc_' . mt_rand(100, 999) . '_' . time() . '.' . $file_ext;

						if (move_uploaded_file($file_data['tmp_name'], VEHICLE_DOC_PATH . $file_name_final)) {
							$this->resize($file_name_final, 300, 240, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_LARGE);
							$this->resize($file_name_final, 150, 120, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_THUMB);

							unlink(VEHICLE_DOC_PATH . $file_name_final);

							

							//update with new file
							$this->Vehicle->updateAll(array('Vehicle.insurance_policy_doc' => "'" . $file_name_final . "'", 'Vehicle.is_insurance_policy_doc_approved' => "'P'", 'Vehicle.status' => "'I'"), array('Vehicle.id' => $last_id));
						}
					}
				}
				
				$this->Session->setFlash('Vehicle has been addded successfully', 'success');
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
		
		
		$this->User->bindModel(
            array(
                'hasMany' => array(
                    'VehicleDetail' => array(
                        'className' => 'Vehicles',
                        'foreignKey' => 'user_id'
                    )
                )
            ), false
        );
		
		$result_data = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
		
		$this->request->data = $result_data;
		
		
		if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }
		
		$order = 'Booking.id DESC';
		
		$u_type = $this->Session->read('User.user_type');
		if($u_type == 'B'){
			
			if($this->Session->read('User.driver_status') == 'A' && $this->Session->read('User.customer_status') == 'A')
				$u_type = 'D';
			elseif($this->Session->read('User.driver_status') == 'A' && $this->Session->read('User.customer_status') == 'I'){
				$u_type = 'D';
			}
			else{
				
				$u_type = 'B';
			}
			
		}
		
		if($u_type == 'D')
			$conditions['Booking.driver_id'] = $user_id;
		else
			$conditions['Booking.user_id'] = $user_id;
		
		$query = array(
	    'joins' => array(
		array(
		    'table' => 'users',
		    'alias' => 'User',
		    'type' => 'LEFT',
		    'conditions' => array(
			'User.id = Booking.user_id'
		    )
		),
		array(
		    'table' => 'users',
		    'alias' => 'Driver',
		    'type' => 'LEFT',
		    'conditions' => array(
			'Driver.id = Booking.driver_id'
		    )
		)
	    ),
	    'fields' => array('User.first_name','User.last_name', 'Driver.first_name','Driver.last_name','Booking.id','Booking.price','Booking.total_miles','Booking.booking_type','Booking.booking_status','Booking.pickup_date','Booking.journey_start_date','Booking.journey_end_date','Booking.created'),
            'conditions' => $conditions,
            'order' => $order
        );
        if('all' == $limit){
            $result_data1 = $this->Booking->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $result_data1 = $this->paginate('Booking');
        }
		
		
		$countries = $this->CountryLocale->find('list',
            array(
                'fields' => array('CountryLocale.country_id', 'CountryLocale.name'),
                'conditions' => array('CountryLocale.lang_code' => 'en'),
                'order' => array('CountryLocale.name' => 'ASC')
            )
        );
		
		$states = $this->StateLocale->find('list',
            array(
                'fields' => array('StateLocale.state_id', 'StateLocale.name'),
                'conditions' => array('StateLocale.lang_code' => 'en'),
                'order' => array('StateLocale.name' => 'ASC')
            )
        );
		
		
		
		if($u_type == 'D'){
		
		
			if($this->Session->check('page_size1')){
				$limit = $this->Session->read('page_size1');
			}


		
		
			$conditions1['Driver.id ='] = $user_id;
			$query1 = array(
			'fields' => array(
				'SUM(Transaction.driver_amount) AS total_driver_amount',
				'SUM(Transaction.admin_amount) AS total_admin_amount',
				'Transaction.driver_amount',
				'Transaction.admin_amount',
				'Transaction.admin_percent',
				'GROUP_CONCAT(Transaction.id separator \',\') as transaction_id',
				'sum(Booking.total_miles) AS total_miles',
				'Driver.first_name',
				'Driver.last_name',
				'Transaction.status',
				'Booking.journey_end_date',
				'Booking.journey_start_date',
				'WEEK(Transaction.created) AS week',
				'sum(Transaction.driver_amount) AS price',
				'CONCAT(DATE_FORMAT(DATE_ADD(`Transaction`.`created`, INTERVAL(1-DAYOFWEEK(`Transaction`.`created`)) DAY),\'%Y-%m-%e\'), \' TO \',    
				 DATE_FORMAT(DATE_ADD(`Transaction`.`created`, INTERVAL(7-DAYOFWEEK(`Transaction`.`created`)) DAY),\'%Y-%m-%e\')) AS date_range',
			),
			'joins' => array(
				array(
				'conditions' => array(
					'Transaction.booking_id = Booking.id',
				),
				'table' => 'bookings',
				'alias' => 'Booking',
				'type' => 'left',
				),
				array(
				'conditions' => array(
					'Driver.id = Booking.driver_id',
				),
				'table' => 'users',
				'alias' => 'Driver',
				'type' => 'left',
				),
			),
			'conditions' => $conditions1,
			'group' => array(
				'Booking.driver_id','YEARWEEK(Transaction.created)',
			),
			'contain' => array(
				'Booking','Driver',
			),
			'order' => $order,
			'limit' => $limit,    
			); 
		
			$this->paginate = $query1;
				$result_data2 = $this->paginate('Transaction');
		
			$this->loadModel('CategoryLocale');
			$categories = $this->CategoryLocale->find('list', array('fields' => array('CategoryLocale.category_id', 'CategoryLocale.name'), 'conditions' => array('CategoryLocale.lang_code' => 'en' /*'Category.status' => 'A'*/), 'order' => array('CategoryLocale.name' => 'ASC')));
			
			$this->loadModel('MakeLocale');
			$makes = $this->MakeLocale->find('list', array('fields' => array('MakeLocale.make_id', 'MakeLocale.name'), 'conditions' => array('MakeLocale.lang_code' => 'en' /*'Make.status' => 'A'*/), 'order' => array('MakeLocale.name' => 'ASC')));

			$this->loadModel('ModelLocale');
			$models = $this->ModelLocale->find('list',
				array(
					'fields' => array('ModelLocale.model_id', 'ModelLocale.name'),
					'conditions' => array('ModelLocale.lang_code' => 'en'),
					'order' => array('ModelLocale.name' => 'ASC')
				)
			);
			
			$this->set(compact('result_data1', 'limit', 'order','user_id','countries','states','result_data2','result_data','makes','categories','models')); 
			$this->render('driver_dashboard');
		}
		else {
			$this->set(compact('result_data1', 'limit', 'order','user_id','countries','states','result_data')); 
			$this->render('dashboard');
		}
		
		

    }
	
	
	function change_password() {

        $this->layout = false;
        $this->autoRender = false;

        $id = $this->Session->read('User.id');
        

        
        if (!empty($this->request->data)) {

            $this->User->set($this->request->data);

            if ($this->User->validates()) {

                $new_password = Security::hash($this->request->data['password'], 'md5');

                $this->User->updateAll(array('User.password' => "'" . $new_password . "'"), array('User.id' => $id));
                $this->Session->setFlash('Password has been updated successfully', 'success');
                $this->redirect(array('plugin' => false, 'controller' => 'home', 'action' => 'dashboard', 'admin' => false));
            }
        }
    }
	
	
	
	function reset_password() {

        $this->layout = 'default';
		
		$reset_code = $_REQUEST['reset_code'];
		
		$userDetail = $this->User->find('first', array('conditions' => array('User.reset_code' => $reset_code)));
		
		$expire_time = (24*60*60);
		
		$current_time = strtotime('now');
		$fail = FALSE;
		
		if(empty($userDetail) || $userDetail['User']['reset_code'] == ''){
			
			$this->Session->setFlash('Sorry this is wrong request.', 'error');
			$fail = TRUE;
			
		}
		
		
		else if(($current_time - $userDetail['User']['reset_request_sent_on']) > $expire_time){
			
			$this->Session->setFlash('Sorry your request has been expired.', 'error');
			$fail = TRUE;
		}
		
		
		
		$this->set(compact('fail','reset_code'));
		
    }
	
	
    public function contact_us() {

        $this->layout = 'default';
		
		if (!empty($this->request->data)) {

		    $this->request->data['ContactUs']['message'] = $this->request->data['message'];
			$this->request->data['ContactUs']['type'] = $this->request->data['type'];
			
			$viewVars = array('first_name' => $this->request->data['ContactUs']['first_name'],'last_name' => $this->request->data['ContactUs']['last_name'],'email' => $this->request->data['ContactUs']['email'],'reason' => $this->request->data['ContactUs']['reason'],'type' => $this->request->data['ContactUs']['type'],'message' => $this->request->data['ContactUs']['message']);
			
            $this->ContactUs->set($this->request->data);

            if ($this->ContactUs->validates()) {

                $this->ContactUs->save($this->request->data);
				
                $email = 'info@pida.com';
                $subject = "Contact Request Received";
		$this->sendMail($email, $subject, 'contact_us', 'default', $viewVars);
                $this->Session->setFlash('Thanks for contact us.', 'success');
                $this->redirect(array('plugin' => false, 'controller' => 'home', 'action' => 'contact_us', 'admin' => false));
            }
        }

       
		
    }	

}

