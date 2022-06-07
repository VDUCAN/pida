<?php
class DbUpdate{
    
    private $conn;

    function __construct() {
	require_once dirname(__FILE__) . '/DbConnect.php';
	// opening db connection
	$db = new DbConnect();
	$this->conn = $db->connect();
    }
    
    /**
     * This function used to update data in booking_locations table
     * @params array $data update data 
     * @params int $int id primary key
     * @return boolean
     */
    function updateBookingLocations($data = NULL, $id = 0) {
	$table = 'booking_locations';
	$setPart = array();
	$bindings = array();

	foreach ($data as $key => $value) {
	    if ($value != '') {
		$setPart[] = "$key = '$value'";
	    }
	}

	$sql = "UPDATE {$table} SET " . implode(', ', $setPart) . " WHERE id = $id";

	$res = mysqli_query($this->conn, $sql);

	return $res;
    }
    
      /**
     * This function used to update data in bookings table
     * @params array $data update data 
     * @params int $int id primary key
     * @return boolean
     */
    function updateBookings($data = NULL, $id = 0) {
	$table = 'bookings';
	$setPart = array();
	$bindings = array();

	foreach ($data as $key => $value) {
	    if ($value != '') {
		$setPart[] = "$key = '$value'";
	    }
	}

	$sql = "UPDATE {$table} SET " . implode(', ', $setPart) . " WHERE id = $id"; //  echo $sql;die;

	$res = mysqli_query($this->conn, $sql);

	return $res;
    }
    
        /**
     * This function used to update data in creditcards table
     * @params array $data update data 
     * @params int $int id primary key
     * @return boolean
     */
    function updateCreditCards($data = NULL, $id = 0) {
	$table = 'credit_cards';
	$setPart = array();
	$bindings = array();

	foreach ($data as $key => $value) {
	    if ($value != '') {
		$setPart[] = "$key = '$value'";
	    }
	}

	$sql = "UPDATE {$table} SET " . implode(', ', $setPart) . " WHERE id = $id";

	$res = mysqli_query($this->conn, $sql);

	return $res;
    }
    
       
    /**
     * This function used to update data in transactions table
     * @params array $data update data 
     * @params int $int id primary key
     * @return boolean
     */
    function updateTransactions($data = NULL, $id = 0) {
	$table = 'transactions';
	$setPart = array();
	$bindings = array();

	foreach ($data as $key => $value) {
	    if ($value != '') {
		$setPart[] = "$key = '$value'";
	    }
	}

	$sql = "UPDATE {$table} SET " . implode(', ', $setPart) . " WHERE id = $id";

	$res = mysqli_query($this->conn, $sql);

	return $res;
    }
    
    /**
     * This function used to update data in ratings table
     * @params array $data update data 
     * @params int $int id primary key
     * @return boolean
     */
    function updateRatings($data = NULL, $id = 0) {
	$table = 'ratings';
	$setPart = array();
	$bindings = array();

	foreach ($data as $key => $value) {
	    if ($value != '') {
		$setPart[] = "$key = '$value'";
	    }
	}

	$sql = "UPDATE {$table} SET " . implode(', ', $setPart) . " WHERE id = $id";

	$res = mysqli_query($this->conn, $sql);

	return $res;
    }
    
     /**
     * This function used to update data in driver details table
     * @params array $data update data 
     * @params int $int id primary key
     * @return boolean
     */
    function updateDriverDetails($data = NULL, $id = 0) {
	$table = 'driver_details';
	$setPart = array();
	$bindings = array();

	foreach ($data as $key => $value) {
	    if ($value != '') {
		$setPart[] = "$key = '$value'";
	    }
	}

	$sql = "UPDATE {$table} SET " . implode(', ', $setPart) . " WHERE id = $id";

	$res = mysqli_query($this->conn, $sql);

	return $res;
    }
    
     /**
     * This function used to update data in bank details table
     * @params array $data update data 
     * @params int $int id primary key
     * @return boolean
     */
    function updateBankDetails($data = NULL, $id = 0) {
	$table = 'bank_details';
	$setPart = array();
	$bindings = array();

	foreach ($data as $key => $value) {
	    if ($value != '') {
		$setPart[] = "$key = '$value'";
	    }
	}

	$sql = "UPDATE {$table} SET " . implode(', ', $setPart) . " WHERE id = $id";

	$res = mysqli_query($this->conn, $sql);

	return $res;
    }
    
    
}    
?>    