<?php

/**
 * Class CitiesController
 */
class CitiesController extends AppController {

    public $uses = array('City', 'CityLocale', 'Language', 'CountryLocale', 'StateLocale');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'cities');
    }

    /**
     * admin_index For listing of Cities
     * @return mixed
     */
    public function admin_index(){

        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'CityLocale.name ASC';
        $conditions = array();

        if (!empty($this->request->data)) {
            if(isset($this->request->data['City']) && !empty($this->request->data['City'])) {
                $search_data = $this->request->data['City'];
                $this->Session->write('city_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('city_search')){
            $search = $this->Session->read('city_search');
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['CityLocale.name LIKE'] = '%' . $search['search'] . '%';
            }

            if(!empty($search['lang_code'])){
                $conditions['CityLocale.lang_code'] = $search['lang_code'];
            }

            if(!empty($search['status'])){
                $conditions['City.status'] = $search['status'];
            }

            if(!empty($search['country_id'])){
                $conditions['City.country_id'] = $search['country_id'];
            }

            if(!empty($search['state_id'])){
                $conditions['City.state_id'] = $search['state_id'];
            }

        }

        $query = array(
            'conditions' => $conditions,
            'order' => $order
        );
        if('all' == $limit){
            $result_data = $this->CityLocale->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $result_data = $this->paginate('CityLocale');
        }

        $countries = $this->CountryLocale->find('list',
            array(
                'fields' => array('CountryLocale.country_id', 'CountryLocale.name'),
                'conditions' => array('CountryLocale.lang_code' => 'en'),
                'order' => array('CountryLocale.name' => 'ASC')
            )
        );
        $states = $this->StateLocale->find('list',
                array(
                    'fields' => array('StateLocale.state_id', 'StateLocale.name'),
                    'conditions' => array('StateLocale.lang_code' => 'en'),
                    'order' => array('StateLocale.name' => 'ASC')
                )
            );
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language')));
        $this->set(compact('result_data', 'limit', 'order', 'countries', 'states', 'languages'));
    }

    /**
     * To add or edit City
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

        $this->layout = LAYOUT_ADMIN;
        $id = base64_decode($id);
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language'), 'conditions' => array('status' => 'A')));

        if (!empty($this->request->data)) {
            $this->City->set($this->request->data);

            ###### custom validation start for CityLocale name ########
            if('' != $id){
                $conditions = array('CityLocale.city_id !=' => $id);
            }
//pr($this->request->data); die;
            $conditions['City.country_id'] = $this->data['City']['country_id'];
            $conditions['City.state_id'] = $this->data['City']['state_id'];
            foreach ($this->data['CityLocale'] as $lang_code => $val){
                if('' == $val['name']){
                    $this->CityLocale->validationErrors[$lang_code]['name'][] = 'Please Enter City Name';
                }else{
                    $conditions['CityLocale.name'] = $val['name'];
                    $check_unique = $this->CityLocale->find('count', array('conditions' => $conditions, 'limit' => 1));
                    if($check_unique > 0){
                        $this->CityLocale->validationErrors[$lang_code]['name'][] = 'City Name Already Exists';
                    }
                }
            }
            ###### custom validation end for CityLocale name ########

            if ($this->City->validates() && $this->CityLocale->validates()) {
                if ($this->City->save($this->request->data, $validate = false)) {

                    $last_id = $this->City->id;
                    foreach ($this->data['CityLocale'] as $lang_code => $val){

                        $locale_data['CityLocale'] = array(
                            'id' => $val['id'],
                            'city_id' => $last_id,
                            'name' => $val['name'],
                            'lang_code' => $lang_code
                        );
                        $this->CityLocale->save($locale_data, $validate = false);
                    }

                    if('' == $id){
                        $this->Session->setFlash('City has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('City has been updated successfully', 'success');
                    }
                    $this->redirect(array('plugin' => false, 'controller' => 'cities', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){
            $result_data = $this->City->find('first', array('conditions' => array('City.id' => $id)));
            if(empty($result_data)){
                $this->Session->setFlash('Invalid Request !', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'cities', 'action' => 'index', 'admin' => true));
            }

            if (empty($this->request->data)) {

                $tmp = array();
                foreach ($result_data['CityLocale'] as $d){
                    $tmp['CityLocale'][$d['lang_code']] = array(
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

        $states = array();
        if(isset($this->request->data['City']['country_id']) && '' != $this->request->data['City']['country_id']){

            $this->loadModel('StateLocale');
            $states_data = $this->StateLocale->find('all',
                array(
                    'fields' => array('StateLocale.state_id', 'StateLocale.name'),
                    'conditions' => array('StateLocale.lang_code' => 'en', 'State.country_id' => $this->request->data['City']['country_id']),
                    'order' => array('StateLocale.name' => 'ASC')
                )
            );
            foreach ($states_data as $d){
                $states[$d['StateLocale']['state_id']] = $d['StateLocale']['name'];
            }
        }

        $this->set(compact('id', 'languages', 'countries', 'states'));
    }

    /**
     * Change the status of the City
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
            $check_user_exists = $this->City->Find('count', array('conditions' => array('City.id' => $id), 'limit' => 1));
            if (0 == $check_user_exists) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'cities', 'action' => 'index', 'admin' => true));
        }

        $this->City->updateAll(array('City.status' => "'" . $status . "'"), array('City.id' => $id));

        $this->Session->setFlash('City status has been changed successfully', 'success');
        $this->redirect(Router::url( $this->referer(), true ));
    }

}