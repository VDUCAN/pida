<?php

/**
 * Class AjaxController
 */
class AjaxController extends AppController {

    public $uses = false;
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('*','get_vehicle_type','get_states','get_vehicle_model','get_models');
        $this->layout = false;
        $this->autoRender = false;
    }

    /**
     * Get list of states
     * @param string $id
     * @param string $status
     * @return null
     */
    public function get_states() {

        $result_data = array();
        if ($this->request->is('Ajax')) {
            if(isset($this->data['id']) && '' != $this->data['id']) {

                $this->loadModel('StateLocale');
                $id = $this->data['id'];
                $lang = (isset($this->data['lang']) && '' != $this->data['lang']) ? $this->data['lang'] : 'en';
                $show_inactive = (isset($this->data['show_inactive'])) ? $this->data['show_inactive'] : '0';

                $conditions = array('State.country_id' => $id, 'StateLocale.lang_code' => $lang);

                if('1' != $show_inactive){
                    $conditions['State.status'] = 'A';
                }

                $data = $this->StateLocale->find('all',
                    array(
                        'fields' => array('StateLocale.state_id', 'StateLocale.name'),
                        'conditions' => $conditions,
                        'order' => array('StateLocale.name' => 'ASC')
                    )
                );
                foreach ($data as $d){
                    $result_data[$d['StateLocale']['state_id']] = $d['StateLocale']['name'];
                }
            }
        }

        echo json_encode($result_data); die;
    }


    /**
     * Get list of vehicle models
     * @param string $id
     * @param string $status
     * @return null
     */
    public function get_models() {

        $result_data = array();
        if ($this->request->is('Ajax')) {
            if(isset($this->data['id']) && '' != $this->data['id']) {

                $this->loadModel('ModelLocale');
                $id = $this->data['id'];
                $lang = (isset($this->data['lang']) && '' != $this->data['lang']) ? $this->data['lang'] : 'en';
                $show_inactive = (isset($this->data['show_inactive'])) ? $this->data['show_inactive'] : '0';

                $conditions = array('Models.make_id' => $id, 'ModelLocale.lang_code' => $lang);

                if('1' != $show_inactive){
                    $conditions['Models.status'] = 'A';
                }

                $data = $this->ModelLocale->find('all',
                    array(
                        'fields' => array('ModelLocale.model_id', 'ModelLocale.name'),
                        'conditions' => $conditions,
                        'order' => array('ModelLocale.name' => 'ASC')
                    )
                );
                foreach ($data as $d){
                    $result_data[$d['ModelLocale']['model_id']] = $d['ModelLocale']['name'];
                }
            }
        }

        echo json_encode($result_data); die;
    }
	
	public function get_vehicle_type() {

        $result_data = array();
        if ($this->request->is('Ajax')) {
            if(isset($this->data['id']) && '' != $this->data['id']) {

                $this->loadModel('VehicleTypeLocale');
                $id = $this->data['id'];
                $lang = (isset($this->data['lang']) && '' != $this->data['lang']) ? $this->data['lang'] : 'en';
                $show_inactive = (isset($this->data['show_inactive'])) ? $this->data['show_inactive'] : '0';

                $conditions = array('VehicleType.category_id' => $id, 'VehicleTypeLocale.lang_code' => $lang);

                if('1' != $show_inactive){
                    $conditions['VehicleType.status'] = 'A';
                }

                $data = $this->VehicleTypeLocale->find('all',
                    array(
                        'fields' => array('VehicleTypeLocale.vehicle_type_id', 'VehicleTypeLocale.name'),
                        'conditions' => $conditions,
                        'order' => array('VehicleTypeLocale.name' => 'ASC')
                    )
                );
                foreach ($data as $d){
                    $result_data[$d['VehicleTypeLocale']['vehicle_type_id']] = $d['VehicleTypeLocale']['name'];
                }
            }
        }

        echo json_encode($result_data); die;
    }
	
	
	public function get_vehicle_model() {

        $result_data = array();
        if ($this->request->is('Ajax')) {
            if(isset($this->data['id']) && '' != $this->data['id']) {

                $this->loadModel('ModelLocale');
                $id = $this->data['id'];
                $lang = (isset($this->data['lang']) && '' != $this->data['lang']) ? $this->data['lang'] : 'en';
                $show_inactive = (isset($this->data['show_inactive'])) ? $this->data['show_inactive'] : '0';

                $conditions = array('Models.make_id' => $id, 'ModelLocale.lang_code' => $lang);

                if('1' != $show_inactive){
                    $conditions['Models.status'] = 'A';
                }

                $data = $this->ModelLocale->find('all',
                    array(
                        'fields' => array('ModelLocale.model_id', 'ModelLocale.name'),
                        'conditions' => $conditions,
                        'order' => array('ModelLocale.name' => 'ASC')
                    )
                );
                foreach ($data as $d){
                    $result_data[$d['ModelLocale']['model_id']] = $d['ModelLocale']['name'];
                }
            }
        }

        echo json_encode($result_data); die;
    }

}