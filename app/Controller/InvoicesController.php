<?php

/**
 * Class BookingsController
 */
class InvoicesController  extends AppController {

    public $uses = array('Booking', 'BookingLocation' , 'CategoryLocale');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'invoices');
    }

    /**
     * admin_index For listing of bookings
     * @return mixed
     */
    public function admin_index(){ 

        $this->layout = LAYOUT_ADMIN;
        $limit = 'all';
        $order = 'Booking.id DESC';
        $conditions = array(array('Booking.transaction_id !='=>0));
        $this->set('tab_open', 'invoices');
	
	$booking_type = $this->CategoryLocale->find('list', array( 'fields' => array('CategoryLocale.category_id', 'CategoryLocale.name'),'conditions'=>array('CategoryLocale.lang_code'=>'en')));
	
//	pr($booking_type);die;
	
        if (!empty($this->request->data)) { //pr($this->request->data);die;

            if(isset($this->request->data['Invoice']) && !empty($this->request->data['Invoice'])) {
                $search_data = $this->request->data['Invoice'];
                $this->Session->write('invoice_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
	}else{
		$weekFrom = date("Y-m-d", strtotime("this monday - 1 week"));
		$weekTo = date("Y-m-d", strtotime('+6 day',strtotime ($weekFrom)));
		$week_data = $weekFrom.'_'.$weekTo;
		$this->Session->write('invoice_search.week', $week_data);
		
//	        $conditions['Booking.pickup_date >='] = date('Y-m-d',strtotime($weekFrom));
//		$conditions['Booking.pickup_date <='] = date('Y-m-d',strtotime($weekTo));
	}

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('invoice_search')){
            $search = $this->Session->read('invoice_search');
            $order = @$search['order_by'];

            if(!empty($search['search'])){
                $conditions['OR']['User.first_name LIKE'] = '%' . $search['search'] . '%';
		$conditions['OR']['User.last_name LIKE'] = '%' . $search['search'] . '%';
		$conditions['OR']['Driver.first_name LIKE'] = '%' . $search['search'] . '%';
		$conditions['OR']['Driver.last_name LIKE'] = '%' . $search['search'] . '%';
            }

/*            if(!empty($search['booking_status'])){
                $conditions['Booking.booking_status'] = $search['booking_status'];
            } */
	    
	    if(!empty($search['booking_type'])){
                $conditions['CategoryLocale.category_id'] = $search['booking_type'];
            }
	    
	    if(!empty($search['booking_from']) || !empty($search['booking_to'])){
		if(!empty($search['booking_from']) && !empty($search['booking_to'])){
		    $conditions['Booking.pickup_date >='] = date('Y-m-d',strtotime($search['booking_from']));
		    $conditions['Booking.pickup_date <='] = date('Y-m-d',strtotime($search['booking_to']));
		}elseif(!empty($search['booking_from'])){
		    $conditions['Booking.pickup_date >='] = date('Y-m-d',strtotime($search['booking_from']));
		}elseif(!empty($search['booking_to'])){
		    $conditions['Booking.pickup_date <='] = date('Y-m-d',strtotime($search['booking_to']));
		}
	    }else if(!empty($search['week'])){
		$weekArr = explode('_',$search['week']);
		$weekFrom = (!empty($weekArr[0]) ? $weekArr[0] : NULL);
		$weekTo = (!empty($weekArr[1]) ? $weekArr[1] : NULL);
		
		if(!empty($weekFrom) && !empty($weekTo)){
		    $conditions['Booking.pickup_date >='] = date('Y-m-d',strtotime($weekFrom));
		    $conditions['Booking.pickup_date <='] = date('Y-m-d',strtotime($weekTo));
		}
	    }

        }
 
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
		),
		array(
		    'table' => 'vehicle_types',
		    'alias' => 'VehicleType',
		    'type' => 'LEFT',
		    'conditions' => array(
			'VehicleType.id = Booking.vehicle_type_id'
		    )
		),
		array(
		    'table' => 'category_locales',
		    'alias' => 'CategoryLocale',
		    'type' => 'LEFT',
		    'conditions' => array(
			'CategoryLocale.category_id = VehicleType.category_id AND CategoryLocale.lang_code = \'en\''
		    )
		),
		array(
		    'table' => 'transactions',
		    'alias' => 'Transaction',
		    'type' => 'LEFT',
		    'conditions' => array(
			'Transaction.id = Booking.transaction_id'
		    )
		),
	    ),
	    'fields' => array('User.first_name','User.last_name', 'Driver.first_name','Driver.last_name','Booking.id','Booking.price','Booking.total_miles','Booking.booking_type','Booking.booking_status','Booking.pickup_date','Booking.created','Booking.pida_fee','CategoryLocale.name','Transaction.admin_amount','Transaction.driver_amount','Transaction.admin_amount','Transaction.admin_percent','Transaction.admin_pida_fee','Transaction.admin_total_amount'),
            'conditions' => $conditions,
            'order' => $order
        );
	
	
	$sumQuery = array(
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
		),
		array(
		    'table' => 'vehicle_types',
		    'alias' => 'VehicleType',
		    'type' => 'LEFT',
		    'conditions' => array(
			'VehicleType.id = Booking.vehicle_type_id'
		    )
		),
		array(
		    'table' => 'category_locales',
		    'alias' => 'CategoryLocale',
		    'type' => 'LEFT',
		    'conditions' => array(
			'CategoryLocale.category_id = VehicleType.category_id AND CategoryLocale.lang_code = \'en\''
		    )
		),
		array(
		    'table' => 'transactions',
		    'alias' => 'Transaction',
		    'type' => 'LEFT',
		    'conditions' => array(
			'Transaction.id = Booking.transaction_id'
		    )
		),
	    ),
	    'fields' => array('User.first_name','User.last_name', 'Driver.first_name','Driver.last_name','Booking.id','Booking.price','SUM(Booking.price) AS price_sum','Booking.total_miles','Booking.booking_type','Booking.booking_status','Booking.pickup_date','Booking.created','Booking.pida_fee','CategoryLocale.name','Transaction.admin_amount','SUM(Transaction.admin_amount) AS admin_amount_sum','Transaction.driver_amount','Transaction.admin_amount','Transaction.admin_percent','Transaction.admin_pida_fee','SUM(Transaction.admin_pida_fee) AS pida_fee_sum','Transaction.admin_total_amount','SUM(Transaction.admin_total_amount) AS admin_total_amount_sum'),
            'conditions' => $conditions,
            'order' => $order
        );
	
	$sum_data = $this->Booking->find('all', $sumQuery);
	
	//pr($query);die;
	
        if('all' == $limit){
            $result_data = $this->Booking->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $result_data = $this->paginate('Booking');
        }
     //   pr($result_data);
//        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language')));
        $this->set(compact('result_data', 'limit', 'order','booking_type','sum_data')); 
    }

    /**
     * Reset filter
     * @param NULL
     * @return null
     */
    public function admin_reset_filter() {
        $this->autoRender = false;
        $this->Session->delete('invoice_search');
        $this->redirect(array('plugin' => false, 'controller' => 'Invoices', 'action' => 'index', 'admin' => true));
    } 
    
    
     /**
     * To view booking detail
     * @param string $id
     * @return mixed
     */
    function admin_view() { 

	$id = $this->params['named']['id'];
	
	$langCode = DEFAULT_LANGUAGE;

	$this->layout = LAYOUT_ADMIN;

	$this->Booking->bindModel(
		array(
		    'hasOne' => array(
			'User' => array(
			    'className' => 'User',
			    'foreignKey' => false,
			    'conditions' => array('User.id = Booking.user_id'),
			),
			'Driver' => array(
			    'className' => 'User',
			    'foreignKey' => false,
			    'conditions' => array('Driver.id = Booking.driver_id'),
			),
			'Vehicle' => array(
			    'className' => 'Vehicle',
			    'foreignKey' => false,
			    'conditions' => array('Booking.vehicle_id = Vehicle.id'),
			),
			'VehicleType' => array(
			    'className' => 'VehicleType',
			    'foreignKey' => false,
			    'conditions' => array('VehicleType.id = Booking.vehicle_type_id'),
			),
			'Transaction' => array(
			    'className' => 'Transaction',
			    'foreignKey' => false,
			    'conditions' => array('Transaction.id = Booking.transaction_id'),
			),
			'CategoryLocale' => array(
			    'className' => 'CategoryLocale',
			    'foreignKey' => false,
			    'conditions' => array('CategoryLocale.category_id = VehicleType.category_id AND CategoryLocale.lang_code = \'en\''),
			)
		    ),
		    'hasMany' => array(
			'BookingLocation' => array(
			    'className' => 'BookingLocation',
			    'foreignKey' => 'booking_id'
			)
		    )
		)
	);

	if ('' != $id) {
	    $id = base64_decode($id);
	    $this->request->data = $this->Booking->find('first', array('conditions' => array('Booking.id' => $id)));
	}
	$this->set(compact('id'));
    //pr($this->request->data);die;
    }

}
