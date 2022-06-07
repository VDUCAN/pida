<?php

/**
 * Class FaqsController
 */
class FaqsController extends AppController {

    public $uses = array('Faq', 'FaqLocale', 'Language');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'faqs');
    }

    /**
     * For listing of faqs
     * @return mixed
     */
    public function admin_index(){

        $this->layout = 'admin';
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'Faq.created DESC';
        $conditions = array();

        if (!empty($this->request->data)) {

            if(isset($this->request->data['Faq']) && !empty($this->request->data['Faq'])) {
                $search_data = $this->request->data['Faq'];
                $this->Session->write('faq_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('faq_search')){
            $search = $this->Session->read('faq_search');
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['OR'] = array(
                    'FaqLocale.question LIKE' => '%' . $search['search'] . '%',
                    'FaqLocale.answer LIKE' => '%' . $search['search'] . '%'
                );
            }

            if(!empty($search['lang_code'])){
                $conditions['FaqLocale.lang_code'] = $search['lang_code'];
            }

            if(!empty($search['status'])){
                $conditions['Faq.status'] = $search['status'];
            }

        }

        $query = array(
            'conditions' => $conditions,
            'order' => $order
        );
        if('all' == $limit){
            $faqs = $this->FaqLocale->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $faqs = $this->paginate('FaqLocale');
        }

        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language')));
        $this->set(compact('faqs', 'limit', 'order', 'languages'));
    }

    /**
     * To add or edit faq
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

        $this->layout = 'admin';

        $id = base64_decode($id);
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language'), 'conditions' => array('status' => 'A')));

        if (!empty($this->request->data)) {

            ###### custom validation start for FaqLocale ########
            foreach ($this->data['FaqLocale'] as $lang_code => $val){
                if('' == $val['question']){
                    $this->FaqLocale->validationErrors[$lang_code]['question'][] = 'Please Enter Question';
                }

                if('' == $val['answer']){
                    $this->FaqLocale->validationErrors[$lang_code]['answer'][] = 'Please Enter Answer';
                }
            }
            ###### custom validation end for FaqLocale ########

            if ($this->FaqLocale->validates()) {
                if ($this->Faq->save($this->request->data, $validate = false)) {

                    $last_id = $this->Faq->id;
                    foreach ($this->data['FaqLocale'] as $lang_code => $val){

                        $locale_data['FaqLocale'] = array(
                            'id' => $val['id'],
                            'faq_id' => $last_id,
                            'question' => $val['question'],
                            'answer' => $val['answer'],
                            'lang_code' => $lang_code
                        );
                        $this->FaqLocale->save($locale_data, $validate = false);
                    }

                    if('' == $id){
                        $this->Session->setFlash('Faq has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('Faq has been updated successfully', 'success');
                    }
                    $this->redirect(array('plugin' => false, 'controller' => 'faqs', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){
            $result_data = $this->Faq->find('first', array('conditions' => array('Faq.id' => $id)));
            if(empty($result_data)){
                $this->Session->setFlash('Invalid Request !', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'faqs', 'action' => 'index', 'admin' => true));
            }

            if (empty($this->request->data)) {
                $tmp = array();
                foreach ($result_data['FaqLocale'] as $d){
                    $tmp['FaqLocale'][$d['lang_code']] = array(
                        'question' => $d['question'],
                        'answer' => $d['answer'],
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
     * Change the status of the faq
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
            $check_exists = $this->Faq->Find('count', array('conditions' => array('Faq.id' => $id), 'limit' => 1));
            if (0 == $check_exists) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'faqs', 'action' => 'index', 'admin' => true));
        }

        $this->Faq->updateAll(array('Faq.status' => "'" . $status . "'"), array('Faq.id' => $id));

        $this->Session->setFlash('Faq status has been changed successfully', 'success');
        $this->redirect(Router::url( $this->referer(), true ));
    }

    /**
     * delete the faq from database
     * @param string $id
     * @return null
     */
    function delete($id = ''){
        $id = base64_decode($id);
  	$this->Faq->updateAll(array('Faq.is_delete' => "'Y'"), array('Faq.id' => $id));
        $this->Session->setFlash('Faq has been deleted successfully', 'success');
        $this->redirect(array('plugin' => false, 'controller' => 'faqs', 'action' => 'index', 'prefix'=> 'admin'));
    }

}
