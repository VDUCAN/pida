<?php

/**
 * Class BookingsController
 */
class ApisController  extends AppController {

    public $uses = array('Booking', 'BookingLocation','Transaction','BankDetail');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function admin_bookingComplete($bookingId=NULL){

        $this->layout = false;
        $this->autoRender = false;

        require_once ROOT . DS . 'include' . DS . 'DbHandler.php';
        $db = new DbHandler();

        $bookingData = array('booking_id' => $bookingId,'travel_route' => '');

        $response = array();
        $res = $db->bookingCompleted($bookingData);

        if ($res == UNABLE_TO_PROCEED) {
            $msg = $this->getMessage(DEFAULT_LANGUAGE,'UNABLE_TO_PROCEED');
            $this->Session->setFlash($msg, 'error');
        } else if ($res == NO_RECORD_FOUND) { 
            $msg = $this->getMessage(DEFAULT_LANGUAGE,'NO_RECORD_FOUND');
            $this->Session->setFlash($msg, 'error');
        } else if ($res == BOOKING_CANCEL_ERROR) {
            $msg = $this->getMessage(DEFAULT_LANGUAGE,'BOOKING_CANCEL_ERROR');
            $this->Session->setFlash($msg, 'error');
        } else if ($res == BOOKING_COMPLETED_ARRIVED_ERROR) {
            $msg = $this->getMessage(DEFAULT_LANGUAGE,'BOOKING_COMPLETED_ARRIVED_ERROR');
            $this->Session->setFlash($msg, 'error');
        } else if ($res == INVALID_DRIVER_BANK_ACCOUNT) {
            $msg = $this->getMessage(DEFAULT_LANGUAGE,'INVALID_DRIVER_BANK_ACCOUNT');
            $this->Session->setFlash($msg, 'error');
        }else {
            $msg = $this->getMessage(DEFAULT_LANGUAGE,'BOOKING_COMPLETE');
            $this->Session->setFlash($msg, 'success');
        }

       $this->redirect($this->referer());

  //      $this->redirect(array('plugin' => false, 'controller' => 'bookings', 'action' => 'view', 'id' => base64_encode($bookingId), 'admin' => true));
    }

    public function admin_driverArrived($userId=NULL,$bookingLocationId=NULL,$arrived=NULL){

        $this->layout = false;
        $this->autoRender = false;

        require_once ROOT . DS . 'include' . DS . 'DbHandler.php';
        $db = new DbHandler();

        $bookingLocationData = array('driver_id' => $userId,
                                     'arrived' => $arrived,
                                     'booking_location_id'=> $bookingLocationId,
                                    );
        $response = array();
        $res = $db->driverArrived($bookingLocationData);

        if ($res == UNABLE_TO_PROCEED) {
            $msg = $this->getMessage(DEFAULT_LANGUAGE,'UNABLE_TO_PROCEED');
            $this->Session->setFlash($msg, 'error');
        } else if ($res == NO_RECORD_FOUND) { 
            $msg = $this->getMessage(DEFAULT_LANGUAGE,'NO_RECORD_FOUND');
            $this->Session->setFlash($msg, 'error');
        } else if ($res == INVALID_USER) {
            $msg = $this->getMessage(DEFAULT_LANGUAGE,'INVALID_USER');
            $this->Session->setFlash($msg, 'error');
        } else if ($res == INVALID_ARRIVE_TYPE) {
            $msg = $this->getMessage(DEFAULT_LANGUAGE,'INVALID_ARRIVE_TYPE');
            $this->Session->setFlash($msg, 'error');
        }else {
            $msg = $this->getMessage(DEFAULT_LANGUAGE,'DRIVER_ARRIVED');
            $this->Session->setFlash($msg, 'success');
        }

        $this->redirect($this->referer());

//        $this->redirect(array('plugin' => false, 'controller' => 'bookings', 'action' => 'view', 'id' => base64_encode($bookingId), 'admin' => true));
    }
    //Driver Date for Qr Code 
    public function admin_qr_code($driver_id){
        echo "test";exit();
        $qr_data = array();
        $this->loadModel("User");
        $this->User->bindModel(
            array(
                'hasOne' => array(
                    'DriverDetail' => array(
                        'className' => 'DriverDetail',
                        'foreignKey' => 'user_id'
                    )
                )
            )
        );
        $data = $this->User->findById($driver_id);
        //$qr_data['name'] = 

    }
    
}
