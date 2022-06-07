<?php

/**
 * Class CargoTypesController
 */
class StatisticsController  extends AppController {

    public $uses = array('CargoType', 'CargoTypeLocale', 'Language');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'statistics');
    }

    /**
     * admin_index For listing of CargoType
     * @return mixed
     */
    public function admin_index(){ /*

        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'CargoTypeLocale.name ASC';
        $conditions = array();

        if (!empty($this->request->data)) {

            if(isset($this->request->data['CargoType']) && !empty($this->request->data['CargoType'])) {
                $search_data = $this->request->data['CargoType'];
                $this->Session->write('cargo_type_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('cargo_type_search')){
            $search = $this->Session->read('cargo_type_search');
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['CargoTypeLocale.name LIKE'] = '%' . $search['search'] . '%';
            }

            if(!empty($search['lang_code'])){
                $conditions['CargoTypeLocale.lang_code'] = $search['lang_code'];
            }

            if(!empty($search['status'])){
                $conditions['CargoType.status'] = $search['status'];
            }

        }

        $query = array(
            'conditions' => $conditions,
            'order' => $order
        );
        if('all' == $limit){
            $result_data = $this->CargoTypeLocale->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $result_data = $this->paginate('CargoTypeLocale');
        }

        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language')));
        $this->set(compact('result_data', 'limit', 'order', 'languages')); */
    }

    /**
     * To add or edit CargoType
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

        $this->layout = LAYOUT_ADMIN;
        $id = base64_decode($id);
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language'), 'conditions' => array('status' => 'A')));

        if (!empty($this->request->data)) {
            $this->CargoType->set($this->request->data);

            ###### custom validation start for CargoTypeLocale name ########
            if('' != $id){
                $conditions = array('CargoTypeLocale.cargo_type_id !=' => $id);
            }
            foreach ($this->data['CargoTypeLocale'] as $lang_code => $val){
                if('' == $val['name']){
                    $this->CargoTypeLocale->validationErrors[$lang_code]['name'][] = 'Please Enter Cargo Type';
                }else{
                    $conditions['CargoTypeLocale.name'] = $val['name'];
                    $check_unique = $this->CargoTypeLocale->find('count', array('conditions' => $conditions, 'limit' => 1));
                    if($check_unique > 0){
                        $this->CargoTypeLocale->validationErrors[$lang_code]['name'][] = 'Cargo Type Already Exists';
                    }
                }
            }
            ###### custom validation end for CargoTypeLocale name ########

            if ($this->CargoType->validates() && $this->CargoTypeLocale->validates()) {
                if ($this->CargoType->save($this->request->data, $validate = false)) {

                    $last_id = $this->CargoType->id;
                    foreach ($this->data['CargoTypeLocale'] as $lang_code => $val){

                        $locale_data['CargoTypeLocale'] = array(
                            'id' => $val['id'],
                            'cargo_type_id' => $last_id,
                            'name' => $val['name'],
                            'lang_code' => $lang_code
                        );
                        $this->CargoTypeLocale->save($locale_data, $validate = false);
                    }

                    if('' == $id){
                        $this->Session->setFlash('Cargo Type has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('Cargo Type has been updated successfully', 'success');
                    }
                    $this->redirect(array('plugin' => false, 'controller' => 'cargo_types', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){
            $result_data = $this->CargoType->find('first', array('conditions' => array('CargoType.id' => $id)));
            if(empty($result_data)){
                $this->Session->setFlash('Invalid Request !', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'cargo_types', 'action' => 'index', 'admin' => true));
            }

            if (empty($this->request->data)) {
                $tmp = array();
                foreach ($result_data['CargoTypeLocale'] as $d){
                    $tmp['CargoTypeLocale'][$d['lang_code']] = array(
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
     * Change the status of the CargoType
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
            $check_user_exists = $this->CargoType->Find('count', array('conditions' => array('CargoType.id' => $id), 'limit' => 1));
            if (0 == $check_user_exists) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'cargo_types', 'action' => 'index', 'admin' => true));
        }

        $this->CargoType->updateAll(array('CargoType.status' => "'" . $status . "'"), array('CargoType.id' => $id));

        $this->Session->setFlash('Cargo Type status has been changed successfully', 'success');
        $this->redirect(Router::url( $this->referer(), true ));
    }

    /**
     * Delete the CargoType
     * @param string $id
     * @return null
     */
    public function admin_delete($id = '') {

        $id = base64_decode($id);
        $this->CargoType->updateAll(array('CargoType.status' => "'D'"), array('CargoType.id' => $id));

        $this->Session->setFlash('Cargo Type has been deleted successfully', 'success');
        $this->redirect(array('plugin' => false, 'controller' => 'cargo_types', 'action' => 'index', 'admin' => true));

    }

}
