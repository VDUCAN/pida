<?php
/**
 * Class InboxController
 */
class InboxController  extends AppController {

    public $uses = array('FeedbackRequest', 'FeedbackType', 'User');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'inbox');
    }

    /**
     * admin_index For listing of inbox message
     * @return mixed
     */
    public function admin_index(){ 
	
	$this->loadModel('FeedbackRequest');
	$this->loadModel('FeedbackTypeLocales');

        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;
	
	$lang = 'en';
        $order = 'FeedbackRequest.created ASC';
        $conditions = array();

        if (!empty($this->request->data)) {

            if(isset($this->request->data['FeedbackRequest']) && !empty($this->request->data['FeedbackRequest'])) {
                $search_data = $this->request->data['FeedbackRequest'];
                $this->Session->write('inbox_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('inbox_search')){
            $search = $this->Session->read('inbox_search');
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['OR']['User.first_name LIKE'] = '%' . $search['search'] . '%';
		$conditions['OR']['User.last_name LIKE'] = '%' . $search['search'] . '%';
		$conditions['OR']['FeedbackRequest.message LIKE'] = '%' . $search['search'] . '%';
            }
  
	    if(!empty($search['feedback_type_id'])){
                $conditions['FeedbackRequest.feedback_type_id'] = $search['feedback_type_id'];
            }
	    
	    if(!empty($search['status'])){
                $conditions['FeedbackRequest.status'] = $search['status'];
            }
	    
	    if(!empty($search['from']) || !empty($search['to'])){
		if(!empty($search['from']) && !empty($search['to'])){
		    $conditions['FeedbackRequest.created >='] = strtotime($search['from']);
		    $conditions['FeedbackRequest.created <='] = strtotime($search['to']);
		}elseif(!empty($search['from'])){
		    $conditions['FeedbackRequest.created >='] = strtotime($search['from']);
		}elseif(!empty($search['to'])){
		    $conditions['FeedbackRequest.created <='] = strtotime($search['to']);
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
			'FeedbackRequest.user_id = User.id'
		    )
		),
		array(
		    'table' => 'feedback_type_locales',
		    'alias' => 'FeedbackTypeLocale',
		    'type' => 'LEFT',
		    'conditions' => array(
			'FeedbackRequest.feedback_type_id = FeedbackTypeLocale.feedback_type_id',
			'FeedbackTypeLocale.lang_code = \'en\''
		    )
		)
	    ),
	    'fields' => array('User.first_name','User.last_name','FeedbackTypeLocale.name','FeedbackRequest.status','FeedbackRequest.created','FeedbackRequest.id','FeedbackRequest.message'),
            'conditions' => $conditions,
            'order' => $order
        );
        if('all' == $limit){
            $result_data = $this->FeedbackRequest->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $result_data = $this->paginate('FeedbackRequest');
        }

        $feedbackTypes = $this->FeedbackTypeLocales->find('list', array('fields' => array('feedback_type_id', 'name'),'conditions'=>array('lang_code'=>'en')));
        $this->set(compact('result_data', 'limit', 'order','feedbackTypes')); 
    }

    /**
     * To view feedback
     * @param string $id
     * @return mixed
     */
    function admin_view($id = '') {
	
	$this->loadModel('FeedbackTypeLocales');
	$this->loadModel('FeedbackRequest');

        $this->layout = LAYOUT_ADMIN;
        $id = base64_decode($id);
	
	$query = array(
	'joins' => array(
	    array(
		'table' => 'users',
		'alias' => 'User',
		'type' => 'LEFT',
		'conditions' => array(
		    'FeedbackRequest.user_id = User.id'
		)
	    ),
	    array(
		'table' => 'feedback_type_locales',
		'alias' => 'FeedbackTypeLocale',
		'type' => 'LEFT',
		'conditions' => array(
		    'FeedbackRequest.feedback_type_id = FeedbackTypeLocale.feedback_type_id',
		    'FeedbackTypeLocale.lang_code = \'en\''
		)
	    )
	),
	'fields' => array('User.first_name','User.last_name','FeedbackTypeLocale.name','FeedbackRequest.status','FeedbackRequest.created','FeedbackRequest.id','FeedbackRequest.message','FeedbackRequest.reply_msg','FeedbackRequest.user_id'),
	'conditions' => array('FeedbackRequest.id'=>$id),
        );
        
        $result_data = $this->FeedbackRequest->find('first', $query);
	
	if (!empty($this->request->data)) {

            $this->FeedbackRequest->set($this->request->data);

            if ($this->FeedbackRequest->validates()) {
		$postData = $this->request->data;
		$replyMsg = $postData['FeedbackRequest']['reply_msg'];
		$status = 'R';
		$this->FeedbackRequest->updateAll(array('FeedbackRequest.reply_msg' => "'" . $replyMsg . "'",'FeedbackRequest.status'=>"'".$status."'"), array('FeedbackRequest.id' => $id));
		
		$users = $this->User->Find('first', array('fields' => array('User.first_name', 'User.last_name', 'User.email'), 'conditions' => array('User.id' => $result_data['FeedbackRequest']['user_id'])));
		
		$email = $users['User']['email'];
		$name = $users['User']['first_name'].' '.$users['User']['last_name'];
		$queryMsg = $result_data['FeedbackRequest']['message'];
		
		$viewVars = array('name' => $name,'query_msg'=>$queryMsg,'reply_msg'=>$replyMsg);
		$subject = 'Pida: Feedback Reply';

		$this->sendMail($email, $subject, 'feedback_reply', 'default', $viewVars);
		
		$this->Session->setFlash('Reply message has been sent successfully', 'success');
		$this->redirect(array('plugin' => false, 'controller' => 'inbox', 'action' => 'index', 'admin' => true));
	    }
        }

        $this->set(compact('id','result_data'));
    }

    /**
     * Reset filter
     * @param NULL
     * @return null
     */
    public function admin_reset_filter() {
        $this->autoRender = false;
        $this->Session->delete('inbox_search');
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
