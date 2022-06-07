<?php

/**
 * Class CategoriesController
 */
class VehicleTypesController extends AppController {

    public $uses = array('VehicleType', 'VehicleTypeLocale', 'Language');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'vehicle_types');
    }

    /**
     * admin_index For listing of vehicle type
     * @return mixed
     */
    public function admin_index(){

        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'VehicleTypeLocale.name ASC';
        $conditions = array();

        if (!empty($this->request->data)) {

            if(isset($this->request->data['VehicleType']) && !empty($this->request->data['VehicleType'])) {
                $search_data = $this->request->data['VehicleType'];
                $this->Session->write('vehicle_type_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('vehicle_type_search')){
            $search = $this->Session->read('vehicle_type_search');
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['VehicleTypeLocale.name LIKE'] = '%' . $search['search'] . '%';
            }

            if(!empty($search['lang_code'])){
                $conditions['VehicleTypeLocale.lang_code'] = $search['lang_code'];
            }

            if(!empty($search['status'])){
                $conditions['VehicleType.status'] = $search['status'];
            }

            if(!empty($search['category'])){
                $conditions['VehicleType.category_id'] = $search['category'];
            }

        }

        $query = array(
            'conditions' => $conditions,
            'order' => $order
        );
        if('all' == $limit){
            $result_data = $this->VehicleTypeLocale->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $result_data = $this->paginate('VehicleTypeLocale');
        }

        $this->loadModel('CategoryLocale');
        $categories = $this->CategoryLocale->find('list', array('fields' => array('CategoryLocale.category_id', 'CategoryLocale.name'), 'conditions' => array('CategoryLocale.lang_code' => 'en' /*'Category.status' => 'A'*/), 'order' => array('CategoryLocale.name' => 'ASC')));
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language')));

        $this->set(compact('result_data', 'limit', 'order', 'categories', 'languages'));
    }

    /**
     * To add or edit vehicle type
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

        $this->layout = LAYOUT_ADMIN;
        $id = base64_decode($id);
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language'), 'conditions' => array('status' => 'A')));

        if (!empty($this->request->data)) {
            $this->VehicleType->set($this->request->data);

            ###### custom validation start for VehicleTypeLocale name ########
            if('' != $id){
                $conditions = array('VehicleTypeLocale.vehicle_type_id !=' => $id);
            }
            foreach ($this->data['VehicleTypeLocale'] as $lang_code => $val){
                if('' == $val['name']){
                    $this->VehicleTypeLocale->validationErrors[$lang_code]['name'][] = 'Please Enter Vehicle Type';
                }else{
                    $conditions['VehicleTypeLocale.name'] = $val['name'];
                    $check_unique = $this->VehicleTypeLocale->find('count', array('conditions' => $conditions, 'limit' => 1));
                    if($check_unique > 0){
                        $this->VehicleTypeLocale->validationErrors[$lang_code]['name'][] = 'Vehicle Type Already Exists';
                    }
                }
            }
            ###### custom validation end for VehicleTypeLocale name ########

            if ($this->VehicleType->validates() && $this->VehicleTypeLocale->validates()) {
                if ($this->VehicleType->save($this->request->data, $validate = false)) {

                    $last_id = $this->VehicleType->id;
                    foreach ($this->data['VehicleTypeLocale'] as $lang_code => $val){

                        $locale_data['VehicleTypeLocale'] = array(
                            'id' => $val['id'],
                            'vehicle_type_id' => $last_id,
                            'name' => $val['name'],
                            'lang_code' => $lang_code
                        );
                        $this->VehicleTypeLocale->save($locale_data, $validate = false);
                    }

                    if(isset($this->request->data['VehicleType']['vehicle_type_image']) && !empty($this->request->data['VehicleType']['vehicle_type_image'])){
                        $file_data = $this->request->data['VehicleType']['vehicle_type_image'];
                        if(0 === $file_data['error']){
                            $file_name = $file_data['name'];
                            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                            $file_name_final = 'image_' . mt_rand(100, 999) . '_' . time() . '.' . $file_ext;

                            if (move_uploaded_file($file_data['tmp_name'], VEHICLE_TYPE_IMG_PATH . $file_name_final)) {
                                $this->resize($file_name_final, 300, 240, VEHICLE_TYPE_IMG_PATH, VEHICLE_TYPE_IMG_PATH_LARGE);
                                $this->resize($file_name_final, 150, 120, VEHICLE_TYPE_IMG_PATH, VEHICLE_TYPE_IMG_PATH_THUMB);

                                unlink(VEHICLE_TYPE_IMG_PATH . $file_name_final);

                                //remove old file
                                if('' != $id && isset($this->request->data['VehicleType']['recent_photo']) && !empty($this->request->data['VehicleType']['recent_photo'])){
                                    unlink(VEHICLE_TYPE_IMG_PATH_LARGE . $this->request->data['VehicleType']['recent_photo']);
                                    unlink(VEHICLE_TYPE_IMG_PATH_THUMB . $this->request->data['VehicleType']['recent_photo']);
                                }

                                //update with new file
                                $this->VehicleType->updateAll(array('VehicleType.image' => "'" . $file_name_final . "'"), array('VehicleType.id' => $last_id));
                            }
                        }
                    }

                    if('' == $id){
                        $this->Session->setFlash('Vehicle Type has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('Vehicle Type has been updated successfully', 'success');
                    }
                    $this->redirect(array('plugin' => false, 'controller' => 'vehicle_types', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){
            $result_data = $this->VehicleType->find('first', array('conditions' => array('VehicleType.id' => $id)));
            if(empty($result_data)){
                $this->Session->setFlash('Invalid Request !', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'vehicle_types', 'action' => 'index', 'admin' => true));
            }

            if (empty($this->request->data)) {
                $tmp = array();
                foreach ($result_data['VehicleTypeLocale'] as $d){
                    $tmp['VehicleTypeLocale'][$d['lang_code']] = array(
                        'name' => $d['name'],
                        'id' => $d['id']
                    );
                }
                $result_data = array_merge($result_data, $tmp);
                $this->request->data = $result_data;
            }

        }

        $this->loadModel('CategoryLocale');
        $categories = $this->CategoryLocale->find('list', array('fields' => array('CategoryLocale.category_id', 'CategoryLocale.name'), 'conditions' => array('CategoryLocale.lang_code' => 'en' /*'Category.status' => 'A'*/), 'order' => array('CategoryLocale.name' => 'ASC')));
        $this->set(compact('id', 'categories', 'languages'));
    }

    /**
     * Change the status of the vehicle type
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
            $check_user_exists = $this->VehicleType->Find('count', array('conditions' => array('VehicleType.id' => $id), 'limit' => 1));
            if (0 == $check_user_exists) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'vehicle_types', 'action' => 'index', 'admin' => true));
        }

        $this->VehicleType->updateAll(array('VehicleType.status' => "'" . $status . "'"), array('VehicleType.id' => $id));

        $this->Session->setFlash('Vehicle Type status has been changed successfully', 'success');
        $this->redirect(Router::url( $this->referer(), true ));
    }

    /**
     * Delete the vehicle types
     * @param string $id
     * @return null
     */
    public function admin_delete($id = '') {

        $id = base64_decode($id);
        $this->VehicleType->updateAll(array('VehicleType.status' => "'D'"), array('VehicleType.id' => $id));

        $this->Session->setFlash('Vehicle Type has been deleted successfully', 'success');
        $this->redirect(array('plugin' => false, 'controller' => 'vehicle_types', 'action' => 'index', 'admin' => true));

    }

}
