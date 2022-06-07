<?php

/**
 * Class FeedbackTypesController
 */
class FeedbackTypesController extends AppController {

    public $uses = array('FeedbackType', 'FeedbackTypeLocale', 'Language');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'feedback_types');
    }

    /**
     * admin_index For listing of FeedbackType
     * @return mixed
     */
    public function admin_index(){

        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'FeedbackType.created DESC';
        $conditions = array();

        if (!empty($this->request->data)) {

            if(isset($this->request->data['FeedbackType']) && !empty($this->request->data['FeedbackType'])) {
                $search_data = $this->request->data['FeedbackType'];
                $this->Session->write('feedback_type_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('feedback_type_search')){
            $search = $this->Session->read('feedback_type_search');
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['FeedbackTypeLocale.name LIKE'] = '%' . $search['search'] . '%';
            }

            if(!empty($search['lang_code'])){
                $conditions['FeedbackTypeLocale.lang_code'] = $search['lang_code'];
            }

            if(!empty($search['status'])){
                $conditions['FeedbackType.status'] = $search['status'];
            }

        }

        $query = array(
            'conditions' => $conditions,
            'order' => $order
        ); 
        if('all' == $limit){
            $result_data = $this->FeedbackTypeLocale->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $result_data = $this->paginate('FeedbackTypeLocale');
        }

        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language')));
        $this->set(compact('result_data', 'limit', 'order', 'languages'));
    }

    /**
     * To add or edit FeedbackType
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

        $this->layout = LAYOUT_ADMIN;
        $id = base64_decode($id);
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language'), 'conditions' => array('status' => 'A')));

        if (!empty($this->request->data)) {
            $this->FeedbackType->set($this->request->data);

            ###### custom validation start for FeedbackTypeLocale name ########
            if('' != $id){
                $conditions = array('FeedbackTypeLocale.feedback_type_id !=' => $id);
            }
            foreach ($this->data['FeedbackTypeLocale'] as $lang_code => $val){
                if('' == $val['name']){
                    $this->FeedbackTypeLocale->validationErrors[$lang_code]['name'][] = 'Please Enter Feedback Type';
                }else{
                    $conditions['FeedbackTypeLocale.name'] = $val['name'];
                    $check_unique = $this->FeedbackTypeLocale->find('count', array('conditions' => $conditions, 'limit' => 1));
                    if($check_unique > 0){
                        $this->FeedbackTypeLocale->validationErrors[$lang_code]['name'][] = 'Feedback Type Already Exists';
                    }
                }
            }
            ###### custom validation end for FeedbackTypeLocale name ########

            if ($this->FeedbackType->validates() && $this->FeedbackTypeLocale->validates()) {
                if ($this->FeedbackType->save($this->request->data, $validate = false)) {

                    $last_id = $this->FeedbackType->id; 
                    foreach ($this->data['FeedbackTypeLocale'] as $lang_code => $val){

                        $locale_data['FeedbackTypeLocale'] = array(
                            'id' => $val['id'],
                            'feedback_type_id' => $last_id,
                            'name' => $val['name'],
                            'lang_code' => $lang_code
                        );
                        $this->FeedbackTypeLocale->save($locale_data, $validate = false);
                    }

                    if('' == $id){
                        $this->Session->setFlash('Feedback Type has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('Feedback Type has been updated successfully', 'success');
                    }
                    $this->redirect(array('plugin' => false, 'controller' => 'Feedback_types', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){
            $result_data = $this->FeedbackType->find('first', array('conditions' => array('FeedbackType.id' => $id))); 
            if(empty($result_data)){
                $this->Session->setFlash('Invalid Request !', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'Feedback_types', 'action' => 'index', 'admin' => true));
            }

            if (empty($this->request->data)) {
                $tmp = array();
                foreach ($result_data['FeedbackTypeLocale'] as $d){
                    $tmp['FeedbackTypeLocale'][$d['lang_code']] = array(
                        'name' => $d['name'],
                        'id' => $d['id']
                    );
                }
                $result_data = array_merge($result_data, $tmp);
                $this->request->data = $result_data;
            }

        }

        $this->set(compact('id', 'languages'));
    }

    /**
     * Change the status of the FeedbackType
     * @param string $id
     * @param string $status
     * @return null
     */
    public function admin_status($id = '', $status = '') {

        $id = base64_decode($id);

        $is_valid = true;
        if('' == $id || '' == $status){
            $is_valid = false;
        }else{
            $check_user_exists = $this->FeedbackType->Find('count', array('conditions' => array('FeedbackType.id' => $id), 'limit' => 1));
            if (0 == $check_user_exists) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'Feedback_types', 'action' => 'index', 'admin' => true));
        }

        $this->FeedbackType->updateAll(array('FeedbackType.status' => "'" . $status . "'"), array('FeedbackType.id' => $id));

        $this->Session->setFlash('Feedback Type status has been changed successfully', 'success');
        $this->redirect(Router::url( $this->referer(), true ));
    }

    /**
     * Delete the FeedbackType
     * @param string $id
     * @return null
     */
    public function admin_delete($id = '') {

        $id = base64_decode($id);
        $this->FeedbackType->updateAll(array('FeedbackType.status' => "'D'"), array('FeedbackType.id' => $id));

        $this->Session->setFlash('Feedback Type has been deleted successfully', 'success');
        $this->redirect(array('plugin' => false, 'controller' => 'Feedback_types', 'action' => 'index', 'admin' => true));
    }

      /**
     * Delete the FeedbackType
     * @param string $id
     * @return null
     */
    public function admin_reset_filter() {
        $this->autoRender = false;
        $this->Session->delete('feedback_type_search');
        $this->redirect(array('plugin' => false, 'controller' => 'Feedback_types', 'action' => 'index', 'admin' => true));
    } 

}
