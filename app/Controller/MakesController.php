<?php

/**
 * Class MakesController
 */
class MakesController extends AppController {

    public $uses = array('Make', 'MakeLocale', 'Language');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'makes');
    }

    /**
     * admin_index For listing of makes
     * @return mixed
     */
    public function admin_index(){

        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'MakeLocale.name ASC';
        $conditions = array();

        if (!empty($this->request->data)) {

            if(isset($this->request->data['Make']) && !empty($this->request->data['Make'])) {
                $search_data = $this->request->data['Make'];
                $this->Session->write('make_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('make_search')){
            $search = $this->Session->read('make_search');
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['MakeLocale.name LIKE'] = '%' . $search['search'] . '%';
            }
            if(!empty($search['lang_code'])){
                $conditions['MakeLocale.lang_code'] = $search['lang_code'];
            }

            if(!empty($search['status'])){
                $conditions['Make.status'] = $search['status'];
            }

        }

        $query = array(
            'conditions' => $conditions,
            'order' => $order
        );
        if('all' == $limit){
            $result_data = $this->MakeLocale->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $result_data = $this->paginate('MakeLocale');
        }
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language')));
        $this->set(compact('result_data', 'limit', 'order', 'languages'));
    }

    /**
     * To add or edit Make
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

        $this->layout = LAYOUT_ADMIN;
        $id = base64_decode($id);
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language'), 'conditions' => array('status' => 'A')));

        if (!empty($this->request->data)) {

            $this->Make->set($this->request->data);

            ###### custom validation start for MakeLocale name ########
            if('' != $id){
                $conditions = array('MakeLocale.make_id !=' => $id);
            }
            foreach ($this->data['MakeLocale'] as $lang_code => $val){
                if('' == $val['name']){
                    $this->MakeLocale->validationErrors[$lang_code]['name'][] = 'Please Enter Make Name';
                }else{
                    $conditions['MakeLocale.name'] = $val['name'];
                    $check_unique = $this->MakeLocale->find('count', array('conditions' => $conditions, 'limit' => 1));
                    if($check_unique > 0){
                        $this->MakeLocale->validationErrors[$lang_code]['name'][] = 'Make Name Already Exists';
                    }
                }
            }
            ###### custom validation end for MakeLocale name ########

            if ($this->Make->validates() && $this->MakeLocale->validates()) {
                if ($this->Make->save($this->request->data, $validate = false)) {

                    $last_id = $this->Make->id;
                    foreach ($this->data['MakeLocale'] as $lang_code => $val){

                        $locale_data['MakeLocale'] = array(
                            'id' => $val['id'],
                            'make_id' => $last_id,
                            'name' => $val['name'],
                            'lang_code' => $lang_code
                        );
                        $this->MakeLocale->save($locale_data, $validate = false);
                    }

                    if('' == $id){
                        $this->Session->setFlash('Make has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('Make has been updated successfully', 'success');
                    }
                    $this->redirect(array('plugin' => false, 'controller' => 'makes', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){

            $result_data = $this->Make->find('first', array('conditions' => array('Make.id' => $id)));
            if(empty($result_data)){
                $this->Session->setFlash('Invalid Request !', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'makes', 'action' => 'index', 'admin' => true));
            }

            if (empty($this->request->data)) {
                $tmp = array();
                foreach ($result_data['MakeLocale'] as $d){
                    $tmp['MakeLocale'][$d['lang_code']] = array(
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
     * Change the status of the Make
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
            $check_user_exists = $this->Make->Find('count', array('conditions' => array('Make.id' => $id), 'limit' => 1));
            if (0 == $check_user_exists) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'makes', 'action' => 'index', 'admin' => true));
        }

        $this->Make->updateAll(array('Make.status' => "'" . $status . "'"), array('Make.id' => $id));

        $this->Session->setFlash('Make status has been changed successfully', 'success');
        $this->redirect(Router::url( $this->referer(), true ));
    }

    /**
     * Delete the Make
     * @param string $id
     * @return null
     */
    public function admin_delete($id = '') {

        $id = base64_decode($id);
        $this->Make->updateAll(array('Make.status' => "'D'"), array('Make.id' => $id));

        $this->Session->setFlash('Make has been deleted successfully', 'success');
        $this->redirect(array('plugin' => false, 'controller' => 'makes', 'action' => 'index', 'admin' => true));

    }

}
