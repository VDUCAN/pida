<?php

class VehiclesController extends AppController {

    public $uses = array('User', 'Vehicle');
    public $components = array('Session', 'Paginator');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'vehicles');
    }

    /**
     * To list drivers
     * @return mixed
     */
    function admin_index($type = ''){

       if(!in_array($type, array('', 'approved', 'pending'))){
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
        } 

        $this->set('tab_open', 'vehicles'.$type);

        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;

        $order = 'Vehicle.created DESC';

        $conditions = array();

        $this->loadModel('MakeLocale');
        $this->loadModel('ModelLocale');

        $session_name = $type.'vehicle_search';

        if (!empty($this->request->data)) {

            if(isset($this->request->data['Vehicle']) && !empty($this->request->data['Vehicle'])) {
                $search_data = $this->request->data['Vehicle'];
                $this->Session->write($session_name, $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check($session_name)){
            $search = $this->Session->read($session_name);
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['OR'] = array(
                    'CONCAT(User.first_name, " ", User.last_name) LIKE' => '%' . $search['search'] . '%',
                    'Vehicle.make_year LIKE' => '%' . $search['search'] . '%',
                    'Vehicle.plate_no LIKE' => '%' . $search['search'] . '%',
                    'Vehicle.registration_no LIKE' => '%' . $search['search'] . '%',
                    'Vehicle.insurance_policy_no LIKE' => '%' . $search['search'] . '%'
                );
            }

            if(!empty($search['make_id'])){
                $conditions['Vehicle.make_id'] = $search['make_id'];
            }

            if(!empty($search['model_id'])){
                $conditions['Vehicle.model_id'] = $search['model_id'];
            }

            if(!empty($search['registered_from'])){
                $conditions['Vehicle.created >='] = strtotime($search['registered_from']);
            }
            if(!empty($search['registered_till'])){
                $conditions['Vehicle.created <='] = strtotime($search['registered_till'] . ' 23:59:59');
            }

            if(!empty($search['is_registration_doc_approved'])){
                $conditions['Vehicle.is_registration_doc_approved'] = $search['is_registration_doc_approved'];
            }

            if(!empty($search['is_insurance_policy_doc_approved'])){
                $conditions['Vehicle.is_insurance_policy_doc_approved'] = $search['is_insurance_policy_doc_approved'];
            }

        }

        //pr($conditions);

        if('approved' == $type){
            $conditions['Vehicle.status'] = 'A';
            /*$conditions['Vehicle.is_registration_doc_approved'] = 'A';
            $conditions['Vehicle.is_insurance_policy_doc_approved'] = 'A';*/

        }
        elseif('pending' == $type){

            $conditions['Vehicle.status'] = 'I';

            /*$conditions[]['OR'] = array(
                'Vehicle.is_registration_doc_approved !=' => 'A',
                'Vehicle.is_insurance_policy_doc_approved !=' => 'A'
            );*/
        }

        //pr($conditions); die;

        $this->Vehicle->bindModel(
            array(
                'belongsTo' => array(
                    'User' => array(
                        'className' => 'User',
                        'foreignKey' => 'user_id'
                    )
                )
            ), false
        );

        $this->User->bindModel(
            array(
                'hasOne' => array(
                    'DriverDetail' => array(
                        'className' => 'DriverDetail',
                        'foreignKey' => 'user_id'
                    )
                )
            ), false
        );

        $query = array(
            'conditions' => $conditions,
            'order' => $order
        );
        $this->Vehicle->recursive = 2;
        if('all' == $limit){
            $result_data = $this->Vehicle->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $result_data = $this->paginate('Vehicle');
        }

        $this->loadModel('VehicleTypeLocale');
        foreach ($result_data as $k => $r){

            $vehicle_types = array();
            if(!empty($r['Vehicle']['vehicle_type_id'])) {
//                $v_types = explode(',', $r['Vehicle']['vehicle_type']);
                $v_types = $r['Vehicle']['vehicle_type_id'];

                $vehicle_types_data = $this->VehicleTypeLocale->find('all',
                    array(
                       // 'fields' => array('VehicleTypeLocale.name'),
                        'conditions' => array('VehicleTypeLocale.vehicle_type_id' => $v_types, 'VehicleTypeLocale.lang_code' => 'en'),
                        'order' => array('VehicleTypeLocale.name' => 'ASC'),
                        'recursive' => 3)
                );

                foreach ($vehicle_types_data as $v_type){
                    if(isset($v_type['VehicleType']['Category']) && !empty($v_type['VehicleType']['Category'])){
                        foreach ($v_type['VehicleType']['Category']['CategoryLocale'] as $cat){
                            if('en' == $cat['lang_code']){
                                $vehicle_types[] = $v_type['VehicleTypeLocale']['name'] . ' (' . $cat['name'] . ')';
                                break;
                            }
                        }
                    }
                }

            }
            $result_data[$k]['Vehicle']['vehicle_type'] = implode(', ', $vehicle_types);
        }

//pr($result_data); die;

        $makes = $this->MakeLocale->find('list',
            array(
                'fields' => array('MakeLocale.make_id', 'MakeLocale.name'),
                'conditions' => array('MakeLocale.lang_code' => 'en'),
                'order' => array('MakeLocale.name' => 'ASC')
            )
        );

        $models = $this->ModelLocale->find('list',
            array(
                'fields' => array('ModelLocale.model_id', 'ModelLocale.name'),
                'conditions' => array('ModelLocale.lang_code' => 'en'),
                'order' => array('ModelLocale.name' => 'ASC')
            )
        );


        $this->set(compact('result_data', 'limit', 'order', 'makes', 'models', 'type', 'session_name'));
    }

    /**
     * To add or edit vehicle
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

        $this->layout = LAYOUT_ADMIN;

        if (!empty($this->request->data)) {

            //pr($this->request->data); //die;

            //vehicle_type array imploded comma as in database vehicle_type is stored as comma seperated
/*            if(isset($this->request->data['Vehicle']['vehicle_type']) && is_array($this->request->data['Vehicle']['vehicle_type'])) {
                $this->request->data['Vehicle']['vehicle_type'] = implode(',', $this->request->data['Vehicle']['vehicle_type']);
            } */

            //pr($this->request->data); die;
            $this->Vehicle->set($this->request->data);
            if ($this->Vehicle->validates()) {

                if ($this->Vehicle->save($this->request->data, $validate = false)) {

                    $last_id = $this->Vehicle->id;

                    if(isset($this->request->data['Vehicle']['registration_doc_file']) && !empty($this->request->data['Vehicle']['registration_doc_file'])){
                        $file_data = $this->request->data['Vehicle']['registration_doc_file'];
                        if(0 === $file_data['error']){
                            $file_name = $file_data['name'];
                            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                            $file_name_final = 'registration_doc_' . mt_rand(100, 999) . '_' . time() . '.' . $file_ext;

                            if (move_uploaded_file($file_data['tmp_name'], VEHICLE_DOC_PATH . $file_name_final)) {
                                $this->resize($file_name_final, 300, 240, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_LARGE);
                                $this->resize($file_name_final, 150, 120, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_THUMB);

                             //   unlink(VEHICLE_DOC_PATH . $file_name_final);

                                //remove old file
                                if('' != $id && isset($this->request->data['Vehicle']['recent_registration_doc_file']) && !empty($this->request->data['Vehicle']['recent_registration_doc_file'])){
                                    unlink(VEHICLE_DOC_PATH_LARGE . $this->request->data['Vehicle']['recent_registration_doc_file']);
                                    unlink(VEHICLE_DOC_PATH_THUMB . $this->request->data['Vehicle']['recent_registration_doc_file']);
                                }

                                //update with new file
                                $this->Vehicle->updateAll(array('Vehicle.registration_doc' => "'" . $file_name_final . "'", 'Vehicle.is_registration_doc_approved' => "'P'", 'Vehicle.status' => "'I'"), array('Vehicle.id' => $last_id));
                            }
                        }
                    }

                    if(isset($this->request->data['Vehicle']['insurance_policy_doc_file']) && !empty($this->request->data['Vehicle']['insurance_policy_doc_file'])){
                        $file_data = $this->request->data['Vehicle']['insurance_policy_doc_file'];
                        if(0 === $file_data['error']){
                            $file_name = $file_data['name'];
                            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                            $file_name_final = 'insurance_policy_doc_' . mt_rand(100, 999) . '_' . time() . '.' . $file_ext;

                            if (move_uploaded_file($file_data['tmp_name'], VEHICLE_DOC_PATH . $file_name_final)) {
                                $this->resize($file_name_final, 300, 240, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_LARGE);
                                $this->resize($file_name_final, 150, 120, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_THUMB);

                            //    unlink(VEHICLE_DOC_PATH . $file_name_final);

                                //remove old file
                                if('' != $id && isset($this->request->data['Vehicle']['recent_insurance_policy_doc_file']) && !empty($this->request->data['Vehicle']['recent_insurance_policy_doc_file'])){
                                    unlink(VEHICLE_DOC_PATH_LARGE . $this->request->data['Vehicle']['recent_insurance_policy_doc_file']);
                                    unlink(VEHICLE_DOC_PATH_THUMB . $this->request->data['Vehicle']['recent_insurance_policy_doc_file']);
                                }

                                //update with new file
                                $this->Vehicle->updateAll(array('Vehicle.insurance_policy_doc' => "'" . $file_name_final . "'", 'Vehicle.is_insurance_policy_doc_approved' => "'P'", 'Vehicle.status' => "'I'"), array('Vehicle.id' => $last_id));
                            }
                        }
                    }

                    if('' == $id){
                        $this->Session->setFlash('Vehicle has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('Vehicle has been updated successfully', 'success');
                    }

                    $this->redirect(array('plugin' => false, 'controller' => 'vehicles', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){
            $id = base64_decode($id);
            $result_data = $this->Vehicle->find('first', array('conditions' => array('Vehicle.id' => $id)));
            if(empty($result_data)){
                $this->Session->setFlash('Invalid Request !', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'vehicles', 'action' => 'index', 'admin' => true));
            }

            if (empty($this->request->data)) {
                $this->request->data = $result_data;
            }
            $this->request->data['Vehicle']['registration_doc'] = $result_data['Vehicle']['registration_doc'];
            $this->request->data['Vehicle']['insurance_policy_doc'] = $result_data['Vehicle']['insurance_policy_doc'];
        }

        //vehicle_type exploded by comma as in database vehicle_type is stored as comma seperated
/*        if(isset($this->request->data['Vehicle']['vehicle_type']) && !is_array($this->request->data['Vehicle']['vehicle_type'])){
            $this->request->data['Vehicle']['vehicle_type'] = explode(',', $this->request->data['Vehicle']['vehicle_type']);
        } */

        $this->loadModel('MakeLocale');
        $this->loadModel('VehicleTypeLocale');

        $vehicle_types = $makes = $models = array();

        $this->User->virtualFields = array(
            'name' => 'CONCAT(User.first_name, " ", User.last_name, " (", User.email, ")")'
        );
        $users = $this->User->find('list', array('fields' => array('User.id', 'User.name'),
                'conditions' => array('User.user_type' => array('D', 'B')),
                'order' => array('User.first_name' => 'ASC', 'User.last_name' => 'ASC')
            )
        );

        $this->VehicleTypeLocale->recursive = 3;
        $vehicle_types_data = $this->VehicleTypeLocale->find('all', array(
                'conditions' => array('VehicleTypeLocale.lang_code' => 'en'),
                'order' => array('VehicleTypeLocale.name' => 'ASC'),
                'recursive' => 3
            )
        );

        foreach ($vehicle_types_data as $v_type){
            if(isset($v_type['VehicleType']['Category']) && !empty($v_type['VehicleType']['Category'])){
                foreach ($v_type['VehicleType']['Category']['CategoryLocale'] as $cat){
                    if('en' == $cat['lang_code']){
                        $vehicle_types[$v_type['VehicleType']['id']] = $v_type['VehicleTypeLocale']['name'] . ' (' . $cat['name'] . ')';
                        break;
                    }
                }
            }
        }

        $makes = $this->MakeLocale->find('list',
            array(
                'fields' => array('MakeLocale.make_id', 'MakeLocale.name'),
                'conditions' => array('MakeLocale.lang_code' => 'en'),
                'order' => array('MakeLocale.name' => 'ASC')
            )
        );

        if(isset($this->request->data['Vehicle']['make_id']) && '' != $this->request->data['Vehicle']['make_id']){

            $this->loadModel('ModelLocale');
            $model_data = $this->ModelLocale->find('all',
                array(
                    'fields' => array('ModelLocale.model_id', 'ModelLocale.name'),
                    'conditions' => array('ModelLocale.lang_code' => 'en', 'Models.make_id' => $this->request->data['Vehicle']['make_id']),
                    'order' => array('ModelLocale.name' => 'ASC')
                )
            );
            foreach ($model_data as $d){
                $models[$d['ModelLocale']['model_id']] = $d['ModelLocale']['name'];
            }
        }

        $this->set(compact('id', 'makes', 'users', 'vehicle_types', 'models'));
    }

    /**
     * approve vehicle registration document
     * @param string $id
     * @param string $status
     * @return null
     */
    public function admin_approve_registration_doc($id = '', $status = '') {

        $id = base64_decode($id);

        $is_valid = true;
        if('' == $id || '' == $status){
            $is_valid = false;
        }else{
            $result_data = $this->Vehicle->Find('first', array('conditions' => array('Vehicle.id' => $id)));
            if (empty($result_data)) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'vehicles', 'action' => 'index', 'admin' => true));
        }

        if('R' == $status){
            $this->Vehicle->updateAll(array('Vehicle.is_registration_doc_approved' => "'" . $status . "'", 'Vehicle.status' => "'I'"), array('Vehicle.id' => $id));
            $this->Session->setFlash('Registration document has been rejected successfully', 'success');
        }
        elseif('A' == $status){
            if(!empty($result_data['Vehicle']['registration_no']) && !empty($result_data['Vehicle']['registration_doc'])){
                $this->Vehicle->updateAll(array('Vehicle.is_registration_doc_approved' => "'" . $status . "'"), array('Vehicle.id' => $id));
                $this->Session->setFlash('Registration document has been approved successfully', 'success');
            }else{
                $this->Session->setFlash('Unable to approve registration document due to incomplete vehicle registraion details !', 'error');
            }

        }else{
            $this->Session->setFlash('Invalid Request !', 'error');
        }

        $this->redirect(Router::url( $this->referer(), true ));
    }

    /**
     * approve vehicle insurance document
     * @param string $id
     * @param string $status
     * @return null
     */
    public function admin_approve_insurance_doc($id = '', $status = '') {

        $id = base64_decode($id);

        $is_valid = true;
        if('' == $id || '' == $status){
            $is_valid = false;
        }else{
            $result_data = $this->Vehicle->Find('first', array('conditions' => array('Vehicle.id' => $id)));
            if (empty($result_data)) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'vehicles', 'action' => 'index', 'admin' => true));
        }

        if('R' == $status){
            $this->Vehicle->updateAll(array('Vehicle.is_insurance_policy_doc_approved' => "'" . $status . "'", 'Vehicle.status' => "'I'"), array('Vehicle.id' => $id));
            $this->Session->setFlash('Insurance policy document has been rejected successfully', 'success');
        }
        elseif('A' == $status){
            if(!empty($result_data['Vehicle']['insurance_policy_no']) && !empty($result_data['Vehicle']['insurance_policy_doc']) && !empty($result_data['Vehicle']['insurance_expiry_date'])){

                $expiry_date = strtotime($result_data['Vehicle']['insurance_expiry_date'] . ' 23:59:59');

                if($expiry_date > time()){
                    $this->Vehicle->updateAll(array('Vehicle.is_insurance_policy_doc_approved' => "'" . $status . "'"), array('Vehicle.id' => $id));
                    $this->Session->setFlash('Insurance policy document has been approved successfully', 'success');
                }
                else{
                    $this->Session->setFlash('Unable to approve insurance policy document due to vehicle insurance policy exired !', 'error');
                }
            }else{
                $this->Session->setFlash('Unable to approve insurance policy document due to incomplete vehicle insurance policy details !', 'error');
            }

        }else{
            $this->Session->setFlash('Invalid Request !', 'error');
        }

        $this->redirect(Router::url( $this->referer(), true ));
    }


    /**
     * update status of vehicle
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
            $result_data = $this->Vehicle->Find('first', array('conditions' => array('Vehicle.id' => $id)));
            if (empty($result_data)) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'vehicles', 'action' => 'index', 'admin' => true));
        }

        if('I' == $status){
            $this->Vehicle->updateAll(array('Vehicle.status' => "'" . $status . "'"), array('Vehicle.id' => $id));
            $this->Session->setFlash('Vehicle has been inactivated successfully', 'success');
        }
        elseif('A' == $status){
            if('A' == $result_data['Vehicle']['is_registration_doc_approved'] && 'A' == $result_data['Vehicle']['is_insurance_policy_doc_approved']){

                $this->Vehicle->updateAll(array('Vehicle.status' => "'" . $status . "'"), array('Vehicle.id' => $id));
                $this->Session->setFlash('Vehicle has been activated successfully', 'success');

            }else{
                $this->Session->setFlash('Failed, Please approve all the documents of the vehicle before activate this vehicle !', 'error');
            }

        }else{
            $this->Session->setFlash('Invalid Request !', 'error');
        }

        $this->redirect(Router::url( $this->referer(), true ));
    }


    /**
     * assign vehicle to driver
     * @param string $vehicle_id
     * @param string $user_id
     * @return null
     */
    public function admin_assign_driver($vehicle_id = '', $user_id = '') {

        $vehicle_id = base64_decode($vehicle_id);
        $user_id = base64_decode($user_id);

        if('' == $vehicle_id || '' == $user_id){
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'vehicles', 'action' => 'index', 'admin' => true));
        }else{

            $this->loadModel('DriverDetail');

            $check_user_exists = $this->DriverDetail->Find('first', array('fields' => array('DriverDetail.id'), 'conditions' => array('DriverDetail.user_id' => $user_id), 'limit' => 1));
            if (!empty($check_user_exists)) {
                $this->DriverDetail->updateAll(array('DriverDetail.user_id' => "'" . $user_id . "'", 'DriverDetail.vehicle_id' => "'" . $vehicle_id . "'"), array('DriverDetail.id' => $check_user_exists['DriverDetail']['id']));
            }else{

                $data['DriverDetail'] = array(
                    'user_id' => $user_id,
                    'vehicle_id' => $vehicle_id
                );

                $this->DriverDetail->save($data, false);
            }
        }

        $this->Session->setFlash('Vehicle has been assigned successfully to the driver', 'success');
        $this->redirect(Router::url( $this->referer(), true ));
    }

    function admin_delete($id=NULL) {
	
//	echo 'test',$id;die;
	
	$this->layout = false;
	$this->render(false);
	$item_id = $id;
	if (!$item_id) {
	    echo json_encode(array('succ' => 0, 'msg' => 'Invalid Request, Vehicle id not found'));
	    die;
	} else {
	    // fetch order's of user
	    $userData = $this->Vehicle->find('first', array(
		'conditions' => array(
		    'Vehicle.id' => $item_id
		),
		'fields' => array(
		    'Vehicle.id'
		),
		'recursive' => -1
	    ));

	    if (!empty($userData)) {  //echo $userData['User']['id'];die;
		if ($this->Vehicle->delete($userData['Vehicle']['id'])) {


		    echo json_encode(array('succ' => 1, 'msg' => 'Vehicle deleted successfully'));
		    die;
		} else {

		    echo json_encode(array('succ' => 0, 'msg' => 'Vehicle couldn\'t be deleted, please try again later'));
		    die;
		}
	    }
	  //  exit;
	}
	exit;
    }

}
