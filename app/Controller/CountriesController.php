<?php

/**
 * Class CountriesController
 */
class CountriesController extends AppController {

    public $uses = array('Country', 'CountryLocale', 'Language');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'countries');
    }

    /**
     * admin_index For listing of Countries
     * @return mixed
     */
    public function admin_index(){

        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'Country.country_code ASC, CountryLocale.name ASC';
        $conditions = array();

        if (!empty($this->request->data)) {
            if(isset($this->request->data['Country']) && !empty($this->request->data['Country'])) {
                $search_data = $this->request->data['Country'];
                $this->Session->write('country_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('country_search')){
            $search = $this->Session->read('country_search');
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['OR'] = array(
                    'Country.country_code LIKE' => '%' . $search['search'] . '%',
                    'CountryLocale.name LIKE' => '%' . $search['search'] . '%'
                );
            }

            if(!empty($search['lang_code'])){
                $conditions['CountryLocale.lang_code'] = $search['lang_code'];
            }

            if(!empty($search['status'])){
                $conditions['Country.status'] = $search['status'];
            }

        }

        $query = array(
            'conditions' => $conditions,
            'order' => $order
        );
        if('all' == $limit){
            $result_data = $this->CountryLocale->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $result_data = $this->paginate('CountryLocale');
        }

        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language')));
        $this->set(compact('result_data', 'limit', 'order', 'languages'));
    }

    /**
     * To add or edit Country
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

        $this->layout = LAYOUT_ADMIN;
        $id = base64_decode($id);
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language'), 'conditions' => array('status' => 'A')));

        if (!empty($this->request->data)) {
            $this->Country->set($this->request->data);

            ###### custom validation start for CountryLocale name ########
            if('' != $id){
                $conditions = array('CountryLocale.country_id !=' => $id);
            }
            foreach ($this->data['CountryLocale'] as $lang_code => $val){
                if('' == $val['name']){
                    $this->CountryLocale->validationErrors[$lang_code]['name'][] = 'Please Enter Country Name';
                }else{
                    $conditions['CountryLocale.name'] = $val['name'];
                    $check_unique = $this->CountryLocale->find('count', array('conditions' => $conditions, 'limit' => 1));
                    if($check_unique > 0){
                        $this->CountryLocale->validationErrors[$lang_code]['name'][] = 'Country Name Already Exists';
                    }
                }
            }
            ###### custom validation end for CountryLocale name ########

            if ($this->Country->validates() && $this->CountryLocale->validates()) {
                if ($this->Country->save($this->request->data, $validate = false)) {

                    $last_id = $this->Country->id;
                    foreach ($this->data['CountryLocale'] as $lang_code => $val){

                        $locale_data['CountryLocale'] = array(
                            'id' => $val['id'],
                            'country_id' => $last_id,
                            'name' => $val['name'],
                            'lang_code' => $lang_code
                        );
                        $this->CountryLocale->save($locale_data, $validate = false);
                    }

                    if('' == $id){
                        $this->Session->setFlash('Country has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('Country has been updated successfully', 'success');
                    }
                    $this->redirect(array('plugin' => false, 'controller' => 'countries', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){
            $result_data = $this->Country->find('first', array('conditions' => array('Country.id' => $id)));
            if(empty($result_data)){
                $this->Session->setFlash('Invalid Request !', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'countries', 'action' => 'index', 'admin' => true));
            }

            if (empty($this->request->data)) {

                $tmp = array();
                foreach ($result_data['CountryLocale'] as $d){
                    $tmp['CountryLocale'][$d['lang_code']] = array(
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
     * Change the status of the Country
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
            $check_user_exists = $this->Country->Find('count', array('conditions' => array('Country.id' => $id), 'limit' => 1));
            if (0 == $check_user_exists) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'countries', 'action' => 'index', 'admin' => true));
        }

        $this->Country->updateAll(array('Country.status' => "'" . $status . "'"), array('Country.id' => $id));

        $this->Session->setFlash('Country status has been changed successfully', 'success');
        $this->redirect(Router::url( $this->referer(), true ));
    }

}
