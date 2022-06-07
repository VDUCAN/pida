<?php
/**
 * Class InboxController
 */
class ContactController  extends AppController {

    public $uses = array('ContactUs');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'contact');
    }

    /**
     * admin_index For listing of inbox message
     * @return mixed
     */
    public function admin_index(){ 
	
	$this->loadModel('ContactUs');
	

        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;
	
	$lang = 'en';
        $order = 'ContactUs.created_at ASC';
        $conditions = array();

        if (!empty($this->request->data)) {

            if(isset($this->request->data['ContactUs']) && !empty($this->request->data['ContactUs'])) {
                $search_data = $this->request->data['ContactUs'];
                $this->Session->write('contact_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('contact_search')){
            $search = $this->Session->read('contact_search');
            //$order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['OR']['ContactUs.first_name LIKE'] = '%' . $search['search'] . '%';
		$conditions['OR']['ContactUs.last_name LIKE'] = '%' . $search['search'] . '%';
		$conditions['OR']['ContactUs.reason LIKE'] = '%' . $search['search'] . '%';
		$conditions['OR']['ContactUs.message LIKE'] = '%' . $search['search'] . '%';
		$conditions['OR']['ContactUs.email LIKE'] = '%' . $search['search'] . '%';
            }
  
	    if(!empty($search['type'])){
                $conditions['ContactUs.type'] = $search['type'];
            }
	    

	    
	    
        }

        $query = array(
	    
	    'fields' => array('ContactUs.first_name','ContactUs.last_name','ContactUs.email','ContactUs.reason','ContactUs.type','ContactUs.message','ContactUs.created_at'),
            'conditions' => $conditions,
            'order' => $order
        );
        if('all' == $limit){
            $result_data = $this->ContactUs->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $result_data = $this->paginate('ContactUs');
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
        $this->Session->delete('contact_search');
        $this->redirect(array('action' => 'index'));
    } 
    
    
    public function admin_test(){
	
		$email = 'jspanwarbwr@gmail.com';
		$name = 'Jaswant singh';
		$queryMsg = 'Hello';
		$replyMsg = 'fine';
		
		$viewVars = array('name' => $name,'query_msg'=>$queryMsg,'reply_msg'=>$replyMsg);
		$subject = 'Pida: Feedback Reply';

		$this->sendMail($email, $subject, 'feedback_reply', 'default', $viewVars);
	
    }

}

