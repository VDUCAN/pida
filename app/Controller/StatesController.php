<?php

/**
 * Class StatesController
 */
class StatesController extends AppController {

    public $uses = array('State', 'StateLocale', 'Language', 'CountryLocale');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'states');
    }

    /**
     * admin_index For listing of States
     * @return mixed
     */
    public function admin_index(){

        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'StateLocale.name ASC';
        $conditions = array();

        if (!empty($this->request->data)) {
            if(isset($this->request->data['State']) && !empty($this->request->data['State'])) {
                $search_data = $this->request->data['State'];
                $this->Session->write('state_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('state_search')){
            $search = $this->Session->read('state_search');
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['StateLocale.name LIKE'] = '%' . $search['search'] . '%';
            }

            if(!empty($search['lang_code'])){
                $conditions['StateLocale.lang_code'] = $search['lang_code'];
            }

            if(!empty($search['status'])){
                $conditions['State.status'] = $search['status'];
            }

            if(!empty($search['country_id'])){
                $conditions['State.country_id'] = $search['country_id'];
            }

        }

        $query = array(
            'conditions' => $conditions,
            'order' => $order
        );
        if('all' == $limit){
            $result_data = $this->StateLocale->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $result_data = $this->paginate('StateLocale');
        }

        $countries = $this->CountryLocale->find('list',
            array(
                'fields' => array('CountryLocale.country_id', 'CountryLocale.name'),
                'conditions' => array('CountryLocale.lang_code' => 'en'),
                'order' => array('CountryLocale.name' => 'ASC')
            )
        );
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language')));
        $this->set(compact('result_data', 'limit', 'order', 'countries', 'languages'));
    }

    /**
     * To add or edit State
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

        $this->layout = LAYOUT_ADMIN;
        $id = base64_decode($id);
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language'), 'conditions' => array('status' => 'A')));

        if (!empty($this->request->data)) {
            $this->State->set($this->request->data);

            ###### custom validation start for StateLocale name ########
            if('' != $id){
                $conditions = array('StateLocale.state_id !=' => $id);
            }

            $conditions['State.country_id'] = $this->data['State']['country_id'];
            foreach ($this->data['StateLocale'] as $lang_code => $val){
                if('' == $val['name']){
                    $this->StateLocale->validationErrors[$lang_code]['name'][] = 'Please Enter State Name';
                }else{
                    $conditions['StateLocale.name'] = $val['name'];
                    $check_unique = $this->StateLocale->find('count', array('conditions' => $conditions, 'limit' => 1));
                    if($check_unique > 0){
                        $this->StateLocale->validationErrors[$lang_code]['name'][] = 'State Name Already Exists';
                    }
                }
            }
            ###### custom validation end for StateLocale name ########

            if ($this->State->validates() && $this->StateLocale->validates()) {
                if ($this->State->save($this->request->data, $validate = false)) {

                    $last_id = $this->State->id;
                    foreach ($this->data['StateLocale'] as $lang_code => $val){

                        $locale_data['StateLocale'] = array(
                            'id' => $val['id'],
                            'state_id' => $last_id,
                            'name' => $val['name'],
			    'state_code' => $val['state_code'],
                            'lang_code' => $lang_code
                        );
                        $this->StateLocale->save($locale_data, $validate = false);
                    }

                    if('' == $id){
                        $this->Session->setFlash('State has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('State has been updated successfully', 'success');
                    }
                    $this->redirect(array('plugin' => false, 'controller' => 'states', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){
            $result_data = $this->State->find('first', array('conditions' => array('State.id' => $id)));
            if(empty($result_data)){
                $this->Session->setFlash('Invalid Request !', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'states', 'action' => 'index', 'admin' => true));
            }

            if (empty($this->request->data)) {

                $tmp = array();
                foreach ($result_data['StateLocale'] as $d){
                    $tmp['StateLocale'][$d['lang_code']] = array(
			'state_code' => $d['state_code'],
                        'name' => $d['name'],
                        'id' => $d['id']
                    );
                }
                $result_data = array_merge($result_data, $tmp);
                $this->request->data = $result_data;
            }

        }

        $countries = $this->CountryLocale->find('list',
            array(
                'fields' => array('CountryLocale.country_id', 'CountryLocale.name'),
                'conditions' => array('CountryLocale.lang_code' => 'en'),
                'order' => array('CountryLocale.name' => 'ASC')
            )
        );
        $this->set(compact('id', 'languages', 'countries'));
    }

    /**
     * Change the status of the State
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
            $check_user_exists = $this->State->Find('count', array('conditions' => array('State.id' => $id), 'limit' => 1));
            if (0 == $check_user_exists) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'states', 'action' => 'index', 'admin' => true));
        }

        $this->State->updateAll(array('State.status' => "'" . $status . "'"), array('State.id' => $id));

        $this->Session->setFlash('State status has been changed successfully', 'success');
        $this->redirect(Router::url( $this->referer(), true ));
    }

}