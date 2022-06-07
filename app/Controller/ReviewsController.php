<?php

/**
 * Class CargoTypesController
 */
class ReviewsController  extends AppController {

    public $uses = array('Booking', 'BookingLocation','Rating','User');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'reviews');
    }

    /**
     * admin_index For listing of CargoType
     * @return mixed
     */
    public function admin_index(){ 

        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'Booking.id DESC';
        $conditions = array();

        if (!empty($this->request->data)) {

            if(isset($this->request->data['Booking']) && !empty($this->request->data['Booking'])) {
                $search_data = $this->request->data['Booking'];
                $this->Session->write('review_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('review_search')){
            $search = $this->Session->read('review_search');
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['OR']['User.first_name LIKE'] = '%' . $search['search'] . '%';
		$conditions['OR']['User.last_name LIKE'] = '%' . $search['search'] . '%';
		$conditions['OR']['Driver.first_name LIKE'] = '%' . $search['search'] . '%';
		$conditions['OR']['Driver.last_name LIKE'] = '%' . $search['search'] . '%';
            }

            if(!empty($search['booking_status'])){
                $conditions['Booking.booking_status'] = $search['booking_status'];
            }
	    
	    if(!empty($search['booking_type'])){
                $conditions['Booking.booking_type'] = $search['booking_type'];
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
	    }
        }

        $query = array(
	    'joins' => array(
		array(
		    'table' => 'bookings',
		    'alias' => 'Booking',
		    'type' => 'LEFT',
		    'conditions' => array(
			'Rating.booking_id = Booking.id'
		    )
		),
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
	    'fields' => array('Rating.id','Rating.rating','Rating.comment','User.first_name','User.last_name', 'Driver.first_name','Driver.last_name','Booking.id','Booking.price','Booking.total_miles','Booking.booking_type','Booking.booking_status','Booking.pickup_date','Booking.created'),
            'conditions' => $conditions,
            'order' => $order
        );
        if('all' == $limit){
            $result_data = $this->Rating->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $result_data = $this->paginate('Rating');
        }
        $this->set(compact('result_data', 'limit', 'order')); 
    }
    
        /**
     * Reset filter
     * @param NULL
     * @return null
     */
    public function admin_reset_filter() {
        $this->autoRender = false;
        $this->Session->delete('review_search');
        $this->redirect(array('action' => 'index'));
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
	
//	$this->loadModel('Booking');
//	$this->loadModel('BookingLocation');

	$this->Rating->bindModel(
		array(
		    'hasOne' => array(
			'Booking' => array(
			    'className' => 'Booking',
			    'foreignKey' => false,
			    'conditions' => array('Rating.booking_id = Booking.id'),
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
	    $this->request->data = $this->Rating->find('first', array('conditions' => array('Rating.id' => $id)));
	}
	$this->set(compact('id'));
    }



}
