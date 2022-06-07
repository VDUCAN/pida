<?php

/**
 * Class CargoTypesController
 */
class PaymentsController  extends AppController {

    public $uses = array('Transaction', 'Booking','Language');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'payments');
    }

    /**
     * admin_index For listing of CargoType
     * @return mixed
     */
    public function admin_index(){ 

        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'Transaction.created DESC';
        $conditions = array();

        if (!empty($this->request->data)) {

            if(isset($this->request->data['Payments']) && !empty($this->request->data['Payments'])) {
                $search_data = $this->request->data['Payments'];
                $this->Session->write('transaction_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('transaction_search')){
            $search = $this->Session->read('transaction_search');
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['OR']['Driver.first_name LIKE'] = '%' . $search['search'] . '%';
		$conditions['OR']['Driver.last_name LIKE'] = '%' . $search['search'] . '%';
            }


            if(!empty($search['status'])){
                $conditions['Transaction.status'] = $search['status'];
            }
	    
	    if(!empty($search['from']) || !empty($search['to'])){
		if(!empty($search['from']) && !empty($search['to'])){
		    $conditions['Transaction.created >='] = date('Y-m-d',strtotime($search['from']));
		    $conditions['Transaction.created <='] = date('Y-m-d',strtotime($search['to']));
		}elseif(!empty($search['from'])){
		    $conditions['Transaction.created >='] = date('Y-m-d',strtotime($search['from']));
		}elseif(!empty($search['to'])){
		    $conditions['Transaction.created <='] = date('Y-m-d',strtotime($search['to']));
		}
	    }

        }

	
	if('all' == $limit){
		$query = array(
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
		'conditions' => $conditions,
		'group' => array(
		    'YEARWEEK(Transaction.created)',
		),
		'contain' => array(
		    'Booking','Driver',
		),
		'order' => $order,
		'recursive' => -1
	    ); 
	
	    $this->paginate = $query;
            $result_data = $this->paginate('Transaction');
	}else{
		$query = array(
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
		'conditions' => $conditions,
		'group' => array(
		    'Booking.driver_id','YEARWEEK(Transaction.created)',
		),
		'contain' => array(
		    'Booking','Driver',
		),
		'order' => $order,
		'limit' => $limit,
		'recursive' => -1
	    ); 
	
	    $this->paginate = $query;
            $result_data = $this->paginate('Transaction');
	} 
	    
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language')));
	
        $this->set(compact('result_data', 'limit', 'order', 'languages')); 
    }
    
     /**
     * To view booking detail
     * @param string $id
     * @return mixed
     */
    function admin_view() {

	$id = $this->params['named']['id'];
	$encodedId = $id;
	$ids = explode(',',  base64_decode($id));
	
	$date_range = $this->params['named']['date_range'];
	$date_range = base64_decode($date_range);
	
	$langCode = DEFAULT_LANGUAGE;

	$this->layout = LAYOUT_ADMIN;

	$this->Transaction->bindModel(
		array(
		    'hasOne' => array(
			'Booking' => array(
			    'className' => 'Booking',
			    'foreignKey' => false,
			    'conditions' => array('Transaction.booking_id = Booking.id'),
			),
			'User' => array(
			    'className' => 'User',
			    'foreignKey' => false,
			    'conditions' => array('User.id = Booking.user_id'),
			),
			'Driver' => array(
			    'className' => 'User',
			    'foreignKey' => false,
			    'conditions' => array('Driver.id = Booking.driver_id'),
			)
		    )
		)
	);
	
	if (!empty($ids)) {
	    $fields = array('Transaction.reject_reason','Transaction.payment_status','Transaction.status','Transaction.transaction_id','Transaction.driver_amount','Transaction.admin_amount','Transaction.admin_percent','User.first_name','User.last_name', 'Driver.first_name','Driver.last_name','Booking.id','Booking.price','Booking.total_miles','Booking.booking_type','Booking.booking_status','Booking.pickup_date','Booking.created');
	    $result_data = $this->Transaction->find('all', array('conditions' => array('Transaction.id' => $ids),'fields'=>$fields));
	   
	}
	
	$this->set(compact('date_range','result_data','encodedId'));
    }
    
     /**
     * To view booking detail
     * @param string $id
     * @return mixed
     */
    function admin_pay_view() {

	$id = $this->params['named']['id'];
	$encodedId = $id;
	$ids = explode(',',  base64_decode($id));
	
	$date_range = $this->params['named']['date_range'];
	$date_range = base64_decode($date_range);
	
	$langCode = DEFAULT_LANGUAGE;

	$this->layout = LAYOUT_ADMIN;

	$this->Transaction->bindModel(
		array(
		    'hasOne' => array(
			'Booking' => array(
			    'className' => 'Booking',
			    'foreignKey' => false,
			    'conditions' => array('Transaction.booking_id = Booking.id'),
			),
			'User' => array(
			    'className' => 'User',
			    'foreignKey' => false,
			    'conditions' => array('User.id = Booking.user_id'),
			),
			'Driver' => array(
			    'className' => 'User',
			    'foreignKey' => false,
			    'conditions' => array('Driver.id = Booking.driver_id'),
			)
		    )
		)
	);
	
	if (!empty($ids)) {
	    $fields = array('Transaction.status','Transaction.transaction_id','Transaction.driver_amount','Transaction.admin_amount','Transaction.admin_percent','User.first_name','User.last_name', 'Driver.first_name','Driver.last_name','Booking.id','Booking.price','Booking.total_miles','Booking.booking_type','Booking.booking_status','Booking.pickup_date','Booking.created');
	    $result_data = $this->Transaction->find('all', array('conditions' => array('Transaction.id' => $ids),'fields'=>$fields));
	    
	    $fields = array('SUM(Transaction.driver_amount) AS total_driver_amount','SUM(Transaction.admin_amount) AS total_admin_amount');
	    $result_total = $this->Transaction->find('all', array('conditions' => array('Transaction.id' => $ids),'fields'=>$fields));
	}
	
	$this->set(compact('date_range','result_data','encodedId','result_total'));
    }

    /**
     * Reset filter
     * @param NULL
     * @return null
     */
    public function admin_reset_filter() {
        $this->autoRender = false;
        $this->Session->delete('transaction_search');
        $this->redirect(array('action' => 'index'));
    } 
    
    
    /**
     * Change the status of the transcations
     * @param string $id
     * @return null
     */
    public function admin_pay_now_old($id = '') {
	
        $ids = explode(',', base64_decode($id));
	$status = 2;

        $is_valid = true;
        if('' == $id || '' == $status){
            $is_valid = false;
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('action' => 'index'));
        }
	
	
	require_once '../Lib/braintree_php-master/lib/Braintree.php';
	
	Braintree_Configuration::environment(BRAINTREE_ENVIRONMENT);
	Braintree_Configuration::merchantId(BRAINTREE_MERCHANTID);
	Braintree_Configuration::publicKey(BRAINTREE_PUBLICKEY);
	Braintree_Configuration::privateKey(BRAINTREE_PRIVATEKEY);
	
//	$transactionData = $this->Transaction->find('all', array('conditions' => array('Transaction.id' => $ids)));
	$transactionData = $this->Transaction->find('all', array('conditions' => array('Transaction.id' => $ids,'transaction_id !='=>0)));
	
	$checkStatus = false;
	
	foreach($transactionData as $data){
	    $transactionId = $data['Transaction']['transaction_id'];
	    $result = Braintree_Transaction::releaseFromEscrow($transactionId);
	    $inputArr = array('transaction_id'=>$transactionId);
	    $this->paymentlogs($transactionId,$inputArr,$result);
	    if ($result->success) {
		$checkStatus = true;
	    }
	}
	
	if($checkStatus){
	  $this->Transaction->updateAll(array('Transaction.status' => "'" . $status . "'"), array('Transaction.id' => $ids));
	  $this->Session->setFlash('Payment has been done successfully', 'success');
	}else{
	  $this->Session->setFlash('Something went wrong!', 'success');  
	}
	
	$this->redirect(array('action' => 'index'));
        
    }
	
	
	public function admin_pay_now($id = '') {
		$this->autoRender=false;
		$this->autoLayout=false;
		$ids = explode(',', base64_decode($id));
		$status = 2;

		$is_valid = true;
		if('' == $id || '' == $status){
			$is_valid = false;
		}

		if(!$is_valid) {
			$this->Session->setFlash('Invalid Request !', 'error');
			$this->redirect(array('action' => 'index'));
		}
		
		$transactionData = $this->Transaction->find('first', array('fields'=>array("SUM(driver_amount) AS total_driver_amount", "GROUP_CONCAT(Transaction.booking_id) AS all_bookings", "GROUP_CONCAT(Transaction.id) AS all_transactions", "Booking.driver_id"), 'conditions' => array('Transaction.id' => $ids,'Transaction.transaction_id !='=>0)));
		
		//print_r($transactionData);die;

		$checkStatus = false;
		if(!empty($transactionData)){
			//fetching driver's stripe account
			$this->loadModel("User");
			$driver_info = $this->User->findById($transactionData["Booking"]["driver_id"]);
			//print_r($driver_info);die;
			if(isset($driver_info["User"]["stripe_driver_id"]) && !empty($driver_info["User"]["stripe_driver_id"])){
				$amount_to_pay = intval($transactionData[0]["total_driver_amount"]*100);
				$transaction_ids = $transactionData[0]["all_transactions"];
				$booking_ids = $transactionData[0]["all_bookings"];
				
				require_once STRIPE_AUTOLOAD;
				\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
				try{
					$balance = \Stripe\Balance::retrieve();
					$balance_arr = $balance->__toArray(true);
					//print_r($balance_arr);die
					if(isset($balance_arr["available"][0]["amount"]) && $balance_arr["available"][0]["amount"]>$amount_to_pay){
						try{
							$transfer = \Stripe\Transfer::create(array(
							  "amount" => $amount_to_pay,
							  "currency" => "usd",
							  "destination" => $driver_info["User"]["stripe_driver_id"],
							  "metadata" => array(
								"bookings"=>$booking_ids,
								"transactions"=>$transaction_ids,
								"driver"=>$transactionData["Booking"]["driver_id"]
							  ),
							)); 
							$text = print_r($transfer, true);
							$this->saveStripeResponse($text, "DRIVER PAYMENT SUCCESS", $transactionData["Booking"]["driver_id"]);
							$stripe_res = $transfer->__toArray(true);
							$stripe_payment_transfer_id = $stripe_res["id"];
							$this->Transaction->updateAll(array('Transaction.status' => "'" . $status . "'", 'Transaction.stripe_payment_transfer_id' => "'" . $stripe_payment_transfer_id . "'"), array('Transaction.id' => $ids, 'Transaction.transaction_id!=' => 0));
							$this->Session->setFlash('Payment has been done successfully', 'success');
						}
						catch(Exception $e){
							$text = print_r($e->getMessage()."\r\n", true);
							$this->saveStripeResponse($text, "DRIVER PAYMENT FAILURE", $transaction_ids);
							$this->Session->setFlash("Something went wrong: ".$e->getMessage(), 'error');
						}
					}
					else{
						$this->Session->setFlash("Your Stripe account doesn't have sufficient funds.", 'error');
					}
				}
				catch(Exception $e){
					$text = print_r($e->getMessage()."\r\n", true);
					$this->saveStripeResponse($text, "DRIVER PAYMENT FAILURE", $transaction_ids);
					$this->Session->setFlash("Something went wrong: ".$e->getMessage(), 'error');
				}
			}
			else{
				$this->Session->setFlash("This driver doesn't have a Stripe account. In order to receive funds, a driver should have a Stripe account with a bank account linked to it.", 'error');
			}
		}	
		else{
			$this->Session->setFlash("Transaction data was not found.", 'error');
		}
		$this->redirect(array('action' => 'index'));
	}
     /**
     * This function used make logs of payments 
     * @params $params array 
     * @return boolean 
     */
    private function paymentlogs($bookingId=NULL,$input=NULL,$output=NULL) {
	$url = $_SERVER['REQUEST_URI'];
	$input = print_r($input, true);
	$output = print_r($output, true);
	$msg = ' BOOKING ID ' . $bookingId . PHP_EOL .' PAYMENT INPUT ' . $input . PHP_EOL.' PAYMENT OUTPUT ' . $output . PHP_EOL   ;
	$filename = '../../v1/paymentlogs.txt';
	$myfile = fopen($filename, "a") or die("Unable to open file!");
	$txt = "<!---------------------[" . date("Y/m/d h:i:s") . "]----------------------->" . PHP_EOL . $msg . '<!------------------------------End-------------------------------->' . PHP_EOL ;
	fwrite($myfile, $txt);
	fclose($myfile);
	return false;
    }
    

}
