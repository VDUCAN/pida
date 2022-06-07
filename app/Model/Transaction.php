<?php

class Transaction extends AppModel {

    public $name = 'Transaction';
    public $validate = array();
	
	/* public $hasOne = array(
		"Booking"=>array(
			'className' => 'Booking',
            'foreignKey' => 'id'
		)
	); */
	public $belongsTo = array(
		"Booking"=>array(
			'className' => 'Booking',
            'foreignKey' => 'booking_id'
		)
	);
    
}