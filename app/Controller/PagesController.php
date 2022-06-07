<?php

/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Page', 'PageLocale', 'Language');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'pages');
    }


    /**
     * Listing of static pages
     * @return mixed
     */
    public function admin_index(){

        $this->layout = 'admin';
        $limit = DEFAULT_PAGE_SIZE;

        if (!empty($this->request->data)) {

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        $query = array(
            'order' => 'Page.Created DESC, PageLocale.lang_code ASC'
        );
        if('all' == $limit){
            $page_list = $this->PageLocale->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $page_list = $this->paginate('PageLocale');
        }

        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language'), 'conditions' => array('status' => 'A')));
        $this->set(compact('page_list', 'limit', 'languages'));

    }

    /**
     * Add or edit static page
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

        $this->layout = 'admin';

        $id = base64_decode($id);
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language'), 'conditions' => array('status' => 'A')));

        if (!empty($this->request->data)) {
            $this->request->data['Page']['slug'] = $this->converStringToUrl($this->request->data['PageLocale']['en']['name']);

            $this->Page->set($this->request->data);

            ###### custom validation start for PageLocale ########

            foreach ($this->data['PageLocale'] as $lang_code => $val){

                if('en' != $lang_code) {
                    if ('' == $val['name']) {
                        $this->PageLocale->validationErrors[$lang_code]['name'][] = 'Please Enter Page Name';
                    }
                    elseif (strlen($val['name']) > 100) {
                        $this->PageLocale->validationErrors[$lang_code]['name'][] = 'Page Name Should Be Maximum 100 Characters';
                    }
                }

                if('' == $val['body']){
                    $this->PageLocale->validationErrors[$lang_code]['body'][] = 'Please Enter Page Body';
                }
            }
            ###### custom validation end for PageLocale ########

            if ($this->Page->validates() && $this->PageLocale->validates()) {

                if ($this->Page->save($this->request->data, $validate = false)) {

                    $last_id = $this->Page->id;
                    foreach ($this->data['PageLocale'] as $lang_code => $val){

                        $locale_data['PageLocale'] = array(
                            'id' => $val['id'],
                            'page_id' => $last_id,
                            'name' => $val['name'],
                            'body' => $val['body'],
                            'lang_code' => $lang_code
                        );
                        $this->PageLocale->save($locale_data, $validate = false);
                    }

                    if('' == $id){
                        $this->Session->setFlash('Page has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('Page has been updated successfully', 'success');
                    }
                    $this->redirect(array('plugin' => false, 'controller' => 'pages', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){
            $result_data = $this->Page->find('first', array('conditions' => array('Page.id' => $id)));
            if(empty($result_data)){
                $this->Session->setFlash('Invalid Request !', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'pages', 'action' => 'index', 'admin' => true));
            }

            if (empty($this->request->data)) {
                $tmp = array();
                foreach ($result_data['PageLocale'] as $d){
                    $tmp['PageLocale'][$d['lang_code']] = array(
                        'name' => $d['name'],
                        'body' => $d['body'],
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
     * Change the status of the static page
     * @param string $id
     * @param string $status
     * @return null
     */
    function admin_status($id = '', $status = '') {

        $id = base64_decode($id);

        $is_valid = true;
        if('' == $id || '' == $status){
            $is_valid = false;
        }else{
            $check_exists = $this->Page->Find('count', array('conditions' => array('Page.id' => $id), 'limit' => 1));
            if (0 == $check_exists) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'pages', 'action' => 'index', 'admin' => true));
        }

        $this->Page->updateAll(array('Page.status' => "'" . $status . "'"), array('Page.id' => $id));

        $this->Session->setFlash('Page status has been changed successfully', 'success');
        $this->redirect(Router::url( $this->referer(), true ));
    }

    /**
     * delete the Page from databse
     * @param string $id
     * @return null
     */
    function admin_delete($id = ''){
       
        $id = base64_decode($id);
        $this->Page->delete($id);
        $this->PageLocale->deleteAll(array('PageLocale.page_id' => $id));


        $this->Session->setFlash('Page has been deleted successfully', 'success');
        $this->redirect(array('plugin' => false, 'controller' => 'pages', 'action' => 'index', 'admin' => true));

    }

}
