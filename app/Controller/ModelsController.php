<?php

/**
 * Class ModelsController
 */
class ModelsController extends AppController {

    public $uses = array('Models', 'ModelLocale', 'Language');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'models');
    }

    /**
     * admin_index For listing of model
     * @return mixed
     */
    public function admin_index(){

        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'ModelLocale.name ASC';
        $conditions = array();

        if (!empty($this->request->data)) {

            if(isset($this->request->data['Models']) && !empty($this->request->data['Models'])) {
                $search_data = $this->request->data['Models'];
                $this->Session->write('model_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('model_search')){
            $search = $this->Session->read('model_search');
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['ModelLocale.name LIKE'] = '%' . $search['search'] . '%';
            }

            if(!empty($search['lang_code'])){
                $conditions['ModelLocale.lang_code'] = $search['lang_code'];
            }

            if(!empty($search['status'])){
                $conditions['Models.status'] = $search['status'];
            }

            if(!empty($search['make'])){
                $conditions['Models.make_id'] = $search['make'];
            }

        }

        $query = array(
            'conditions' => $conditions,
            'order' => $order
        );
        if('all' == $limit){
            $result_data = $this->ModelLocale->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $result_data = $this->paginate('ModelLocale');
        }

        $this->loadModel('MakeLocale');
        $makes = $this->MakeLocale->find('list', array('fields' => array('MakeLocale.make_id', 'MakeLocale.name'), 'conditions' => array('MakeLocale.lang_code' => 'en' /*'Make.status' => 'A'*/), 'order' => array('MakeLocale.name' => 'ASC')));
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language')));

        $this->set(compact('result_data', 'limit', 'order', 'makes', 'languages'));
    }

    /**
     * To add or edit model
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

        $this->layout = LAYOUT_ADMIN;
        $id = base64_decode($id);
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language'), 'conditions' => array('status' => 'A')));

        if (!empty($this->request->data)) {
            $this->Models->set($this->request->data);

            ###### custom validation start for ModelLocale name ########
            if('' != $id){
                $conditions = array('ModelLocale.model_id !=' => $id);
            }
            //pr($this->request->data); die;
            $conditions['Models.make_id'] = $this->request->data['Models']['make_id'];
            foreach ($this->data['ModelLocale'] as $lang_code => $val){
                if('' == $val['name']){
                    $this->ModelLocale->validationErrors[$lang_code]['name'][] = 'Please Enter Model Name';
                }else{
                    $conditions['ModelLocale.name'] = $val['name'];
                    $check_unique = $this->ModelLocale->find('count', array('conditions' => $conditions, 'limit' => 1));
                    if($check_unique > 0){
                        $this->ModelLocale->validationErrors[$lang_code]['name'][] = 'Model Name Already Exists';
                    }
                }
            }
            ###### custom validation end for ModelLocale name ########

            if ($this->Models->validates() && $this->ModelLocale->validates()) {
                if ($this->Models->save($this->request->data, $validate = false)) {

                    $last_id = $this->Models->id;
                    foreach ($this->data['ModelLocale'] as $lang_code => $val){

                        $locale_data['ModelLocale'] = array(
                            'id' => $val['id'],
                            'model_id' => $last_id,
                            'name' => $val['name'],
                            'lang_code' => $lang_code
                        );
                        $this->ModelLocale->save($locale_data, $validate = false);
                    }

                    if('' == $id){
                        $this->Session->setFlash('Model has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('Model has been updated successfully', 'success');
                    }
                    $this->redirect(array('plugin' => false, 'controller' => 'models', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){
            $result_data = $this->Models->find('first', array('conditions' => array('Models.id' => $id)));
            if(empty($result_data)){
                $this->Session->setFlash('Invalid Request !', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'models', 'action' => 'index', 'admin' => true));
            }

            if (empty($this->request->data)) {
                $tmp = array();
                foreach ($result_data['ModelLocale'] as $d){
                    $tmp['ModelLocale'][$d['lang_code']] = array(
                        'name' => $d['name'],
                        'id' => $d['id']
                    );
                }
                $result_data = array_merge($result_data, $tmp);
                $this->request->data = $result_data;
            }

        }

        $this->loadModel('MakeLocale');
        $makes = $this->MakeLocale->find('list', array('fields' => array('MakeLocale.make_id', 'MakeLocale.name'), 'conditions' => array('MakeLocale.lang_code' => 'en' /*'Make.status' => 'A'*/), 'order' => array('MakeLocale.name' => 'ASC')));
        $this->set(compact('id', 'makes', 'languages'));
    }

    /**
     * Change the status of the model
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
            $check_user_exists = $this->Models->Find('count', array('conditions' => array('Models.id' => $id), 'limit' => 1));
            if (0 == $check_user_exists) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'models', 'action' => 'index', 'admin' => true));
        }

        $this->Models->updateAll(array('Models.status' => "'" . $status . "'"), array('Models.id' => $id));

        $this->Session->setFlash('Model status has been changed successfully', 'success');
        $this->redirect(Router::url( $this->referer(), true ));
    }

}
