<?php

class TestsController extends AppController {

    public $uses = array('User');
    public $components = array('Session', 'Paginator');

    public function beforeFilter() {
        parent::beforeFilter();
    }


    public function admin_test_email(){  

        $email = 'jspanwarbwr@gmail.com';
        $first_name = 'jaswant';
        $last_name = 'singh';
        $message = 'This is test message';
        
        $UserData = array();
        $UserData['first_name']    = $first_name;
        $UserData['email']         = $email;
        $UserData['last_name']     = $last_name;
        $UserData['message']       = $message;

        App::uses("CakeEmail", "Network/Email");
        $Email = new CakeEmail();
        $Email->from('info@pida.com')
            ->to($email)
            ->subject($subject)
            ->template("notification")
            ->emailFormat("html")
            ->viewVars(array('UserData' => $UserData))
            ->send();

        print_r($UserData);
        die;
        
            
    }

     function admin_index() {

        require_once ROOT . DS . 'include' . DS . 'DbHandler.php';
        $db = new DbHandler();
        $result = $db->getCategories('en');
        var_dump($result);
        die;

   /*     $serviceUrl = APIURL . 'booking_complete';
        $params = array('booking_id'=>762,'travel_route'=>'');

        $result = $this->httpPost($serviceUrl,$params); */


        $serviceUrl = APIURL . 'get_categories/en';

        $result = $this->httpGet($serviceUrl);

        var_dump($result);die;

//       To load app/Vendor/services/well.named.php:

        App::import(
                'Vendor',
                'Braintree',
                array('file' => 'braintree' . DS . 'lib' . DS . 'Braintree.php')
            );


        Braintree_Configuration::environment(BRAINTREE_ENVIRONMENT);
        Braintree_Configuration::merchantId(BRAINTREE_PUBLICKEY);
        Braintree_Configuration::publicKey(BRAINTREE_PUBLICKEY);
        Braintree_Configuration::privateKey(BRAINTREE_PRIVATEKEY);


        $result = Braintree_Transaction::sale([
            'amount' => '1000.00',
            'paymentMethodNonce' => 'nonceFromTheClient',
            'options' => [ 'submitForSettlement' => true ]
        ]);

        var_dump($result);
        die;

        if ($result->success) {
            print_r("success!: " . $result->transaction->id);
        } else if ($result->transaction) {
            print_r("Error processing transaction:");
            print_r("\n  code: " . $result->transaction->processorResponseCode);
            print_r("\n  text: " . $result->transaction->processorResponseText);
        } else {
            print_r("Validation errors: \n");
            print_r($result->errors->deepAll());
        }




        phpinfo();
        die;


     }

    /**
     * Change the password of the user by the admin
     * @param string $id
     * @return null
     */
    function admin_change_password() {

        $this->layout = LAYOUT_ADMIN;

        $id = base64_decode($this->params['id']);
        $is_valid = true;
        if('' == $id || !in_array($this->params['type'], array('customers', 'drivers'))){
            $is_valid = false;
        }else{
            $user_data = $this->User->Find('first', array(
                'fields' => array('User.first_name', 'User.last_name'),
                'conditions' => array('User.id' => $id)
            ));

            $this->set(compact('id', 'user_data'));
            if (empty($user_data)) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
        }

        $this->set('tab_open', $this->params['type']);
        if (!empty($this->request->data)) {

            $this->User->set($this->request->data);

            if ($this->User->validates()) {

                $new_password = Security::hash($this->request->data['User']['password'], 'md5');

                $this->User->updateAll(array('User.password' => "'" . $new_password . "'"), array('User.id' => $id));
                $this->Session->setFlash('Password has been updated successfully', 'success');
                $this->redirect(array('plugin' => false, 'controller' => $this->params['type'], 'action' => 'index', 'admin' => true));
            }
        }
    }
	
	

}
