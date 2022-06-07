<?php
/**
 * Class AdminsController
 */
class AdminsController extends AppController {

    public $uses = array('Admin');
    public $components = array('Paginator');

	public $weekdays = array("monday"=>"Monday", "tuesday"=>"Tuesday", "wednesday"=>"Wednesday", "thursday"=>"Thursday", "friday"=>"Friday", "saturday"=>"Saturday");
	
    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {

        parent::beforeFilter();
        $this->Auth->allow('login');
    }

    /**
     * For login admin user
     * @return mixed
     */
    public function admin_login() {

        $this->layout = 'login';
        if ($this->request->is('post')) {
//pr($this->request->data); die;
            if (isset($this->request->data['Admin']['username']) && isset($this->request->data['Admin']['password'])) {
                $username = $this->request->data['Admin']['username'];
                $password = Security::hash($this->data['Admin']['password'], 'md5', false);
                $user = $this->Admin->find('first', array(
                    'conditions' => array(
                        'Admin.email' => $username, 'Admin.password' => $password, 'Admin.status' => 'A'
                    )
                ));
                //pr($user); die;
                if (!empty($user)) {
                    $this->Auth->login($user['Admin']);
                }
                if ($this->Auth->login()) {
                    $this->redirect($this->Auth->loginRedirect);
                } else {
                    $this->Session->setFlash('Invalid Username OR Password.','error');
                }
            }
        }

        if ($this->Auth->loggedIn() || $this->Auth->login()) {
            return $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
        }
    }

    /**
     * For logout admin user
     * @return mixed
     */
    public function admin_logout() {

        $session_id = $this->Session->id();//Get the id of current session
        $this->Auth->logout();
        $this->Session->destroy();//To destroy all session created by logged in user
        $this->Session->id($session_id);//To work logout setFlash session message after destroy session
        $this->Session->setFlash('You have successfully logged out','success');
        $this->redirect(array('controller' => 'admins', 'action' => 'login', 'admin' => true));
        //$this->redirect($this->Auth->logoutRedirect);
    }

    /**
     * Admin dashboard
     * @return mixed
     */
    public function admin_dashboard() {
        $this->layout = LAYOUT_ADMIN;
        $this->set('tab_open','dashboard');

        $this->loadModel('User');

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
        $users = $this->User->find('all');

        //pr($users); die;
        $on_duty_drivers_count = $online_drivers_count = $customers_count = 0;
        foreach ($users as $usr){

            if(in_array($usr['User']['user_type'], array('N', 'B'))){
                $customers_count++;
            }

            if(in_array($usr['User']['user_type'], array('D', 'B'))){
                if(isset($usr['DriverDetail']['is_online']) && 'Y' == $usr['DriverDetail']['is_online'] && $usr['User']['driver_status']=='A'){
                    $online_drivers_count++;
                }
/*                if(isset($usr['DriverDetail']['is_on_duty']) && 'Y' == $usr['DriverDetail']['is_on_duty']){
                    $on_duty_drivers_count++;
                } */
            }
        }
	
	$this->loadModel('Booking');
	
	$sql = 'select count(*) As cnt from bookings where driver_id IS NOT NULL and booking_status = 2 and pickup_date >  DATE( DATE_SUB( NOW() , INTERVAL 1 DAY ) );';
	$driverOnDuty = $this->Booking->query($sql);
	
	if(!empty($driverOnDuty)){
	    $on_duty_drivers_count = $driverOnDuty[0][0]['cnt'];     
	}

        $this->set(compact('on_duty_drivers_count', 'online_drivers_count', 'customers_count'));

    }

    /**
     * Change the password of admin
     * @param string $id
     * @return null
     */
    public function admin_change_password($id = '') {

        $this->set('tab_open', 'admin_users');

        if (!empty($this->request->data)) {
            $this->Admin->set($this->request->data);
            if ($this->Admin->validates()) {
                // update new password
                $uid = $this->request->data['Admin']['id'];
                $new_password = Security::hash($this->request->data['Admin']['confirm_password'], 'md5');
                $this->Admin->updateAll(array('Admin.password' => "'" . $new_password . "'"), array('Admin.id' => $uid));
                $this->Session->setFlash(__('Password has been changed successfully.'), 'success');

                if('' == $id){
                    $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
                }else{
                    $this->redirect(array('controller' => 'admins', 'action' => 'users', 'admin' => true));
                }
            }
        }

        $user_data = array();
        if('' != $id){
            //$this->checkAccess('Admin', 'can_edit');
            $id = base64_decode($id);
            $user_data = $this->Admin->Find('first', array(
                'fields' => array('Admin.firstname', 'Admin.lastname'),
                'conditions' => array('Admin.id' => $id), 'limit' => 1)
            );
            
            if (empty($user_data)) {
                $this->Session->setFlash('Invalid Request !', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'admins', 'action' => 'users', 'admin' => true));
            }
        }

        $this->set(compact('id', 'user_data'));

    }

    /**
     * Listing page of admin users
     * @return mixed
     */
    public function admin_users() {

        //$this->checkAccess('Admin', 'can_view');
        $this->set('tab_open', 'admin_users');
        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'Admin.created DESC';
        $conditions = array('Admin.id !=' => $this->Session->read('Admin.id'), 'Admin.is_super_admin' => 'N');

        if (!empty($this->request->data)) {

            if(isset($this->request->data['Admin']) && !empty($this->request->data['Admin'])) {
                $search_data = $this->request->data['Admin'];
                $this->Session->write('admin_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('admin_search')){
            $search = $this->Session->read('admin_search');
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['OR'] = array(
                    'Admin.firstname LIKE' => '%' . $search['search'] . '%',
                    'Admin.lastname LIKE' => '%' . $search['search'] . '%',
                    'Admin.email LIKE' => '%' . $search['search'] . '%'
                );
            }

            if(!empty($search['status'])){
                $conditions['Admin.status'] = $search['status'];
            }

        }

        $query = array(
            'conditions' => $conditions,
            'order' => $order
        );
        if('all' == $limit){
            $user_list = $this->Admin->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $user_list = $this->paginate();
        }
        $this->set(compact('user_list', 'limit', 'order'));
    }

    /**
     * To add or edit admin user detail
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

        $this->set('tab_open', 'admin_users');
        $this->layout = LAYOUT_ADMIN;
        $id = base64_decode($id);

        /*if('' == $id){
            $this->checkAccess('Admin', 'can_add');
        }
        elseif($id != $this->Session->read('Admin.id')){
            $this->checkAccess('Admin', 'can_edit');
        }*/

        if (!empty($this->request->data)) {
            $this->Admin->set($this->request->data);
            if ($this->Admin->validates()) {

                if(isset($this->request->data['Admin']['password'])) {
                    $this->request->data['Admin']['password'] = Security::hash($this->request->data['Admin']['password'], 'md5');
                }

                if('' == $id){
                    $this->request->data['Admin']['created_by_id'] = $this->Session->read('Admin.id');
                    $this->request->data['Admin']['is_super_admin'] = 'N';
                }

                if ($this->Admin->save($this->request->data, $validate = false)) {

                    if($id == $this->Session->read('Admin.id')){
                        $this->Session->write('Admin.firstname', $this->request->data['Admin']['firstname']);
                        $this->Session->write('Admin.lastname', $this->request->data['Admin']['lastname']);
                        $this->Session->write('Admin.email', $this->request->data['Admin']['email']);
                        $this->Session->setFlash('Profile has been updated successfully', 'success');
                        $this->redirect(array('plugin' => false, 'controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
                    }else{
                        if('' == $id){
                            $this->Session->setFlash('Admin user has been added successfully', 'success');
                        }else{
                            $this->Session->setFlash('Admin user has been updated successfully', 'success');
                        }
                        $this->redirect(array('plugin' => false, 'controller' => 'admins', 'action' => 'users', 'admin' => true));
                    }
                }
            }
        }

        if('' != $id){
            $user_data = $this->Admin->find('first', array('conditions' => array('Admin.id' => $id)));
            if(empty($user_data)){
                $this->Session->setFlash('Invalid Request !', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'admins', 'action' => 'users', 'admin' => true));
            }

            if (empty($this->request->data)) {
                $this->request->data = $user_data;
            }
        }

        $this->set(compact('id'));
    }

    /**
     * Updates the status of the admin user
     * @param string $id
     * @param string $status
     * @return null
     */
    public function admin_status($id = '', $status = '') {

        //$this->checkAccess('Admin', 'can_edit');
        $id = base64_decode($id);

        $is_valid = true;
        if('' == $id || '' == $status){
            $is_valid = false;
        }else{
            $check_user_exists = $this->Admin->Find('count', array('conditions' => array('Admin.id' => $id), 'limit' => 1));
            if (0 == $check_user_exists) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'admins', 'action' => 'users', 'admin' => true));
        }

        $this->Admin->updateAll(array('Admin.status' => "'" . $status . "'"), array('Admin.id' => $id));

        $this->Session->setFlash('Admin user status has been changed successfully', 'success');
        $this->redirect(Router::url( $this->referer(), true ));
    }

    /**
     * assign permissions to sub admin
     * @param string $id
     * @return mixed
     */
    public function admin_permissions($id = '') {

        //$this->checkAccess('AdminPrivilage', 'can_add');

        $this->set('tab_open', 'admin_users');
        $id = base64_decode($id);

        $this->loadModel('AdminPrivilage');

        if(!empty($this->request->data)){

            $this->AdminPrivilage->deleteAll(array('AdminPrivilage.admin_id' => $id));
            $data = $this->request->data;
            foreach ($data as $k => $d){

                $saveData['AdminPrivilage']['id'] = '';
                $saveData['AdminPrivilage']['admin_id'] = $id;
                $saveData['AdminPrivilage']['module'] = $k;
                $saveData['AdminPrivilage']['status'] = 'A';
                $saveData['AdminPrivilage']['can_view'] = (isset($d['view']) && '1' == $d['view']) ? 'Y' : 'N';
                $saveData['AdminPrivilage']['can_add'] = (isset($d['add']) && '1' == $d['add']) ? 'Y' : 'N';
                $saveData['AdminPrivilage']['can_edit'] = (isset($d['edit']) && '1' == $d['edit']) ? 'Y' : 'N';
                $saveData['AdminPrivilage']['can_delete'] = (isset($d['delete']) && '1' == $d['delete']) ? 'Y' : 'N';
                $this->AdminPrivilage->save($saveData, false);
            }

            $this->Session->setFlash('Access permissions has been changed successfully', 'success');
            $this->redirect(array('plugin' => false, 'controller' => 'admins', 'action' => 'users', 'admin' => true));

        }else {

            $permissions = $this->AdminPrivilage->find('all', array('conditions' => array('AdminPrivilage.admin_id' => $id)));

            $prepared_data = array();
            foreach ($permissions as $d) {
                if ('Y' == $d['AdminPrivilage']['can_view']) {
                    $prepared_data[$d['AdminPrivilage']['module']]['view'] = '1';
                }
                if ('Y' == $d['AdminPrivilage']['can_add']) {
                    $prepared_data[$d['AdminPrivilage']['module']]['add'] = '1';
                }
                if ('Y' == $d['AdminPrivilage']['can_edit']) {
                    $prepared_data[$d['AdminPrivilage']['module']]['edit'] = '1';
                }
                if ('Y' == $d['AdminPrivilage']['can_delete']) {
                    $prepared_data[$d['AdminPrivilage']['module']]['delete'] = '1';
                }
            }

            $this->request->data = $prepared_data;
        }

        $user = $this->Admin->find('first', array('fields' => array('Admin.firstname', 'Admin.lastname'), 'conditions' => array('Admin.id' => $id)));

        $name = ucfirst($user['Admin']['firstname']) . ' ' . ucfirst($user['Admin']['lastname']);
        $this->set(compact('name'));

    }

    /**
     * sets global settings for app
     * @param string $id
     * @return mixed
     */
    function admin_global_settings(){
		$this->set('tab_open','global_settings');
		$this->loadModel('GlobalSetting');
		$this->loadModel('Category');



		if (!empty($this->request->data)) {

			$this->GlobalSetting->set($this->request->data['GlobalSetting']);

			if ($this->GlobalSetting->validates()) {
				if ($this->GlobalSetting->save($this->request->data, $validate = false)) {
					$minFares = $this->request->data['minimum_fare'];
					foreach($minFares as $minFare){
						$minimumFare = $minFare['minimum_fare'];
						$cId = $minFare['id'];
						if(!empty($cId)){
							$this->Category->updateAll(array('Category.minimum_fare' => "'" . $minimumFare . "'"), array('Category.id' => $cId));
						}
					}
					//saving global settings in a file
					$path = ROOT_DIRECTORY."global_site_setting.php";
					$data="<?php ";
					$global_setting_data=$this->GlobalSetting->find('first');
					$global_setting_data=$global_setting_data['GlobalSetting'];
					unset($global_setting_data['id']);

					foreach($global_setting_data as $key=>$value){
						$data.="\ndefine('GLOBAL_".strtoupper(str_replace('.','_',$key))."','".$value."');";
					}
					$data.=" \n ?>";
					if(file_exists($path)){
						file_put_contents($path,$data);
					}

					$this->Session->setFlash('Settings has been updated successfully', 'success');
					//$this->redirect(array('plugin' => false, 'controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
					$setting_data = $this->GlobalSetting->find('first');
					$this->request->data = $setting_data;
					
				}
			}
		}else{
			$setting_data = $this->GlobalSetting->find('first');
			$this->request->data = $setting_data;
		}

		$categories = $this->Category->query("SELECT c.id as id,cl.name as name,c.minimum_fare as minimum_fare FROM categories c LEFT JOIN category_locales cl ON c.id = cl.category_id AND cl.lang_code = '".DEFAULT_LANGUAGE."';");
		$this->set(compact('categories'));
		$this->set("weekdays", $this->weekdays);
    }

}
