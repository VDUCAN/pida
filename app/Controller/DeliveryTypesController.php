<?php

/**
 * Class DeliveryTypesController
 */
class DeliveryTypesController extends AppController {

    public $uses = array('DeliveryType', 'DeliveryTypeLocale', 'Language');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'delivery_types');
    }

    /**
     * admin_index For listing of DeliveryType
     * @return mixed
     */
    public function admin_index(){

        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'DeliveryType.created DESC';
        $conditions = array();

        if (!empty($this->request->data)) {

            if(isset($this->request->data['DeliveryType']) && !empty($this->request->data['DeliveryType'])) {
                $search_data = $this->request->data['DeliveryType'];
                $this->Session->write('delivery_type_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('delivery_type_search')){
            $search = $this->Session->read('delivery_type_search');
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['DeliveryTypeLocale.name LIKE'] = '%' . $search['search'] . '%';
            }

            if(!empty($search['lang_code'])){
                $conditions['DeliveryTypeLocale.lang_code'] = $search['lang_code'];
            }

            if(!empty($search['status'])){
                $conditions['DeliveryType.status'] = $search['status'];
            }

        }

        $query = array(
            'conditions' => $conditions,
            'order' => $order
        ); 
        if('all' == $limit){
            $result_data = $this->DeliveryTypeLocale->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $result_data = $this->paginate('DeliveryTypeLocale');
        }

        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language')));
        $this->set(compact('result_data', 'limit', 'order', 'languages'));
    }

    /**
     * To add or edit DeliveryType
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

        $this->layout = LAYOUT_ADMIN;
        $id = base64_decode($id);
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language'), 'conditions' => array('status' => 'A')));

        if (!empty($this->request->data)) {
            $this->DeliveryType->set($this->request->data);

            ###### custom validation start for DeliveryTypeLocale name ########
            if('' != $id){
                $conditions = array('DeliveryTypeLocale.delivery_type_id !=' => $id);
            }
            foreach ($this->data['DeliveryTypeLocale'] as $lang_code => $val){
                if('' == $val['name']){
                    $this->DeliveryTypeLocale->validationErrors[$lang_code]['name'][] = 'Please Enter Delivery Type';
                }else{
                    $conditions['DeliveryTypeLocale.name'] = $val['name'];
                    $check_unique = $this->DeliveryTypeLocale->find('count', array('conditions' => $conditions, 'limit' => 1));
                    if($check_unique > 0){
                        $this->DeliveryTypeLocale->validationErrors[$lang_code]['name'][] = 'Delivery Type Already Exists';
                    }
                }
            }
            ###### custom validation end for DeliveryTypeLocale name ########

            if ($this->DeliveryType->validates() && $this->DeliveryTypeLocale->validates()) {
                if ($this->DeliveryType->save($this->request->data, $validate = false)) {

                    $last_id = $this->DeliveryType->id; 
                    foreach ($this->data['DeliveryTypeLocale'] as $lang_code => $val){

                        $locale_data['DeliveryTypeLocale'] = array(
                            'id' => $val['id'],
                            'delivery_type_id' => $last_id,
                            'name' => $val['name'],
                            'lang_code' => $lang_code
                        );
                        $this->DeliveryTypeLocale->save($locale_data, $validate = false);
                    }

                    if('' == $id){
                        $this->Session->setFlash('Delivery Type has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('Delivery Type has been updated successfully', 'success');
                    }
                    $this->redirect(array('plugin' => false, 'controller' => 'Delivery_types', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){
            $result_data = $this->DeliveryType->find('first', array('conditions' => array('DeliveryType.id' => $id)));
            if(empty($result_data)){
                $this->Session->setFlash('Invalid Request !', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'Delivery_types', 'action' => 'index', 'admin' => true));
            }

            if (empty($this->request->data)) {
                $tmp = array();
                foreach ($result_data['DeliveryTypeLocale'] as $d){
                    $tmp['DeliveryTypeLocale'][$d['lang_code']] = array(
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
     * Change the status of the DeliveryType
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
            $check_user_exists = $this->DeliveryType->Find('count', array('conditions' => array('DeliveryType.id' => $id), 'limit' => 1));
            if (0 == $check_user_exists) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'Delivery_types', 'action' => 'index', 'admin' => true));
        }

        $this->DeliveryType->updateAll(array('DeliveryType.status' => "'" . $status . "'"), array('DeliveryType.id' => $id));

        $this->Session->setFlash('Delivery Type status has been changed successfully', 'success');
        $this->redirect(Router::url( $this->referer(), true ));
    }

    /**
     * Delete the DeliveryType
     * @param string $id
     * @return null
     */
    public function admin_delete($id = '') {

        $id = base64_decode($id);
        $this->DeliveryType->updateAll(array('DeliveryType.status' => "'D'"), array('DeliveryType.id' => $id));

        $this->Session->setFlash('Delivery Type has been deleted successfully', 'success');
        $this->redirect(array('plugin' => false, 'controller' => 'Delivery_types', 'action' => 'index', 'admin' => true));
    }

      /**
     * Delete the DeliveryType
     * @param string $id
     * @return null
     */
    public function admin_reset_filter() {
        $this->autoRender = false;
        $this->Session->delete('delivery_type_search');
        $this->redirect(array('plugin' => false, 'controller' => 'Delivery_types', 'action' => 'index', 'admin' => true));
    } 

}
