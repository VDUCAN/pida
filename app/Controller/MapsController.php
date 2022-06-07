<?php

/**
 * Class CargoTypesController
 */
class MapsController  extends AppController {

    public $uses = array('CargoType', 'CargoTypeLocale', 'Language');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        
    }

    /**
     * admin_index For listing of CargoType
     * @return mixed
     */
    public function admin_index(){
	
	$this->set('tab_open', 'maps');
	
	$this->loadModel('CountryLocale');
	$this->loadModel('StateLocale');
	$this->loadModel('State');
	$this->loadModel('State');
	
	$allcountries = $this->CountryLocale->find('list',
            array(
                'fields' => array('CountryLocale.country_id', 'CountryLocale.name'),
                'conditions' => array('CountryLocale.lang_code' => 'en'),
                'order' => array('CountryLocale.name' => 'ASC')
            )
        );
	
	$this->set('allcountries', $allcountries);
       
    }
    
    public function admin_rider(){
	
	$this->set('tab_open', 'riders');
	
	$this->loadModel('CountryLocale');
	$this->loadModel('StateLocale');
	$this->loadModel('State');
	$this->loadModel('City');
	$this->loadModel('User');
	$this->loadModel('UserLocationLog');
	
//	$allcountries = $this->CountryLocale->find('list',
//            array(
//                'fields' => array('CountryLocale.country_id', 'CountryLocale.name'),
//                'conditions' => array('CountryLocale.lang_code' => 'en'),
//                'order' => array('CountryLocale.name' => 'ASC')
//            )
//        );
	
//	$this->set('allcountries', $allcountries);
			
//			$country_id  = $this->data['country_id'];
//			$stateval_id = $this->data['stateval_id'];
//			$cityval_id  = $this->data['cityval_id'];
             
//            $userdata = $this->User->find('all',array('conditions'=>array('User.country_id'=>$country_id,'User.state_id'=>$stateval_id,'User.city_id'=>$cityval_id,'user_type'=>array('N','B'))));
	    
		$userdata = $this->User->find('all',array('conditions'=>array('user_type'=>array('N','B'))));
			//pr($userdata);die;
			
	        $a = array();
			 foreach($userdata as $parr) {
				 if($parr['User']['lat'] != '') {

					 //$image = (!empty($parr['User']['photo'])) ? USER_IMAGE_STORE_HTTP_PATH . $parr['User']['photo'] : '';
					  $a[] = array(
						  'lat' => $parr['User']['lat'],
						  'lng' => $parr['User']['long'],
						  'name' => ucfirst($parr['User']['first_name']) . ' ' . ucfirst($parr['User']['last_name']),
						  //'image' => $image,
						  //'mobile'=>(!empty($parr['User']['phone'])) ? $parr['User']['phone'] : ''

					) ;
				 }
			 }
			// $this->set('a',$a);
			//pr($a);die;
			$jval=json_encode($a);
			$this->set('jval',$jval);
   
			
		
	
    
    }

    /**
     * To add or edit CargoType
     * @param string $id
     * @return mixed
     */
    public function admin_getlatlong(){

    	$this->layout=false;
    	$this->autoRender = false;
    	if($this->request->is('ajax')){
    		$this->loadModel('City');
    		$this->loadModel('User');
    		$this->loadModel('UserLocationLog');
    		$this->loadModel('DriverDetail');
    		$country_id  = (!empty($this->data['country_id']) ? $this->data['country_id'] : 0);
    		$stateval_id = (!empty($this->data['stateval_id']) ? $this->data['stateval_id'] : 0); 
    		$cityval_id  = (!empty($this->data['cityval_id']) ? $this->data['cityval_id'] : 0); 

    		$conditions = array('user_type'=>array('D','B'),'DriverDetail.is_online'=>'Y','User.driver_status'=>'A');

    		if(!empty($country_id)){
    			$conditions['User.country_id'] = $country_id;
    		}

    		if(!empty($stateval_id)){
    			$conditions['User.state_id'] = $stateval_id;
    		}

    		if(!empty($cityval_id)){
    			$conditions['User.city_id'] = $cityval_id;
    		}

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
    		$userdata = $this->User->find('all',array('conditions'=> $conditions));

   // 		print_r($conditions);die;

    		$a = array();
    		foreach($userdata as $parr) {
    			if($parr['User']['lat'] != '') {
					//$image = (!empty($parr['User']['photo'])) ? USER_IMAGE_STORE_HTTP_PATH . $parr['User']['photo'] : '';
    				$a[] = array(
    					'lat' => $parr['User']['lat'],
    					'lng' => $parr['User']['long'],
    					'name' => ucfirst($parr['User']['first_name']) . ' ' . ucfirst($parr['User']['last_name']),
						  //'image' => $image,
						  //'mobile'=>(!empty($parr['User']['phone'])) ? $parr['User']['phone'] : ''

    				) ;
    			}
    		}

    		echo $jval=json_encode($a);
    	}

    }
        
       
       
        public function admin_getriderlatlong(){
			
	        $this->layout=false;
		    if($this->request->is('ajax')){
			$this->loadModel('City');
			$this->loadModel('User');
			$this->loadModel('UserLocationLog');
			$country_id  = $this->data['country_id'];
			$stateval_id = $this->data['stateval_id'];
			$cityval_id  = $this->data['cityval_id'];
             
//            $userdata = $this->User->find('all',array('conditions'=>array('User.country_id'=>$country_id,'User.state_id'=>$stateval_id,'User.city_id'=>$cityval_id,'user_type'=>array('N','B'))));
	    
		$userdata = $this->User->find('all',array('conditions'=>array('user_type'=>array('N','B'))));
			//pr($userdata);die;
			
	        $a = array();
			 foreach($userdata as $parr) {
				 if($parr['User']['lat'] != '') {

					 //$image = (!empty($parr['User']['photo'])) ? USER_IMAGE_STORE_HTTP_PATH . $parr['User']['photo'] : '';
					  $a[] = array(
						  'lat' => $parr['User']['lat'],
						  'lng' => $parr['User']['long'],
						  'name' => ucfirst($parr['User']['first_name']) . ' ' . ucfirst($parr['User']['last_name']),
						  //'image' => $image,
						  //'mobile'=>(!empty($parr['User']['phone'])) ? $parr['User']['phone'] : ''

					) ;
				 }
			 }
			// $this->set('a',$a);
			//pr($a);die;
			$jval=json_encode($a);
			$this->set('jval',$jval);
       }
			
		}
        
       
        public function admin_getstate(){ 
		//echo 'aaaa';die;
		$this->layout=false;
		if($this->request->is('ajax')){
			
			$this->loadModel('State');
			$this->loadModel('StateLocale');
		//	$statedata = $this->State->find('list',array('conditions'=>array('State.country_id'=>$this->data['id'])),array('fields'=>array('id')));
			//pr($statedata);die;
			$statelist = $this->StateLocale->find('all',array('conditions'=>array('country_id'=>$this->data['id'],'lang_code'=>'en')),array('fields'=>array('state_id','value'=>'name')));
			
			$statelistdata = array();
			foreach ($statelist as $d){
                    $statelistdata[$d['StateLocale']['state_id']] = $d['StateLocale']['name'];
                }
	
			echo json_encode($statelistdata);
            die;
	    }
	}
       
        
	public function admin_getcity(){ 
		$this->layout=false;
		if($this->request->is('ajax')){
			$this->loadModel('City'); 
			$this->loadModel('CityLocale'); 
			
			$citydata = $this->City->find('list',array('conditions'=>array('country_id'=>$this->data['country_id'],'state_id'=>$this->data['stateid'])),array('fields'=>array('id')));
			//pr($citydata);die;
			
			$citylist = $this->CityLocale->find('all',array('conditions'=>array('city_id'=>$citydata,'lang_code'=>'en')),array('fields'=>array('city_id','value'=>'name')));
			
			$citylistdata = array(); 
			foreach ($citylist as $city){
				$citylistdata[$city['CityLocale']['city_id']] = $city['CityLocale']['name'];
			}

			echo json_encode($citylistdata);
			die;
		}
	}
     
     
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
