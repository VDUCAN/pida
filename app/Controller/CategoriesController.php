<?php

/**
 * Class CategoriesController
 */
class CategoriesController extends AppController {

    public $uses = array('Category', 'CategoryLocale', 'Language');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'categories');
    }

    /**
     * admin_index For listing of categories
     * @return mixed
     */
    public function admin_index(){

        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'CategoryLocale.name ASC';
        $conditions = array();

        if (!empty($this->request->data)) {

            if(isset($this->request->data['Category']) && !empty($this->request->data['Category'])) {
                $search_data = $this->request->data['Category'];
                $this->Session->write('category_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('category_search')){
            $search = $this->Session->read('category_search');
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['CategoryLocale.name LIKE'] = '%' . $search['search'] . '%';
            }
            if(!empty($search['lang_code'])){
                $conditions['CategoryLocale.lang_code'] = $search['lang_code'];
            }

            if(!empty($search['status'])){
                $conditions['Category.status'] = $search['status'];
            }

        }

        $query = array(
            'conditions' => $conditions,
            'order' => $order
        );
        if('all' == $limit){
            $categories = $this->CategoryLocale->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $categories = $this->paginate('CategoryLocale');
        }
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language')));
        $this->set(compact('categories', 'limit', 'order', 'languages'));
    }

    /**
     * To add or edit category
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

//        $this->layout = LAYOUT_ADMIN;
        $id = base64_decode($id);
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language'), 'conditions' => array('status' => 'A')));

        if (!empty($this->request->data)) {

            $this->Category->set($this->request->data);

            ###### custom validation start for CategoryLocale name ########
            if('' != $id){
                $conditions = array('CategoryLocale.category_id !=' => $id);
            }
            foreach ($this->data['CategoryLocale'] as $lang_code => $val){
                if('' == $val['name']){
                    $this->CategoryLocale->validationErrors[$lang_code]['name'][] = 'Please Enter Category Name';
                }else{
                    $conditions['CategoryLocale.name'] = $val['name'];
                    $check_unique = $this->CategoryLocale->find('count', array('conditions' => $conditions, 'limit' => 1));
                    if($check_unique > 0){
                        $this->CategoryLocale->validationErrors[$lang_code]['name'][] = 'Category Name Already Exists';
                    }
                }
            }
            ###### custom validation end for CategoryLocale name ########

            if ($this->Category->validates() && $this->CategoryLocale->validates()) {
                if ($this->Category->save($this->request->data, $validate = false)) {

                    $last_id = $this->Category->id;
                    foreach ($this->data['CategoryLocale'] as $lang_code => $val){

                        $locale_data['CategoryLocale'] = array(
                            'id' => $val['id'],
                            'category_id' => $last_id,
                            'name' => $val['name'],
                            'lang_code' => $lang_code
                        );
                        $this->CategoryLocale->save($locale_data, $validate = false);
                    }

                    if('' == $id){
                        $this->Session->setFlash('Category has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('Category has been updated successfully', 'success');
                    }
                    $this->redirect(array('plugin' => false, 'controller' => 'categories', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){

            $result_data = $this->Category->find('first', array('conditions' => array('Category.id' => $id)));
            if(empty($result_data)){
                $this->Session->setFlash('Invalid Request !', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'categories', 'action' => 'index', 'admin' => true));
            }

            if (empty($this->request->data)) {
                $tmp = array();
                foreach ($result_data['CategoryLocale'] as $d){
                    $tmp['CategoryLocale'][$d['lang_code']] = array(
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
     * Change the status of the category
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
            $check_user_exists = $this->Category->Find('count', array('conditions' => array('Category.id' => $id), 'limit' => 1));
            if (0 == $check_user_exists) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'categories', 'action' => 'index', 'admin' => true));
        }

        $this->Category->updateAll(array('Category.status' => "'" . $status . "'"), array('Category.id' => $id));

        $this->Session->setFlash('Category status has been changed successfully', 'success');
        $this->redirect(Router::url( $this->referer(), true ));
    }

    /**
     * Delete the category
     * @param string $id
     * @return null
     */
    public function admin_delete($id = '') {

        $id = base64_decode($id);
        $this->Category->updateAll(array('Category.status' => "'D'"), array('Category.id' => $id));

        $this->Session->setFlash('Category has been deleted successfully', 'success');
        $this->redirect(array('plugin' => false, 'controller' => 'categories', 'action' => 'index', 'admin' => true));

    }

}
