<?php
class DbInsert{
    
    private $conn;

    function __construct() {
	require_once dirname(__FILE__) . '/DbConnect.php';
	// opening db connection
	$db = new DbConnect();
	$this->conn = $db->connect();
    }
/**
     * This function used to insert data in booking_locations table
     * @params array 
     * @return bool
     */
    function insertBookingLocations($data = NULL) {
	$table = 'booking_locations';
	$saveData = array();
	foreach ($data as $key => $val) {
	    if ($val != '') {
		$saveData[$key] = '"' . $val . '"';
	    }
	}
	if (!empty($saveData)) {
	    $columns = implode(',', array_keys($saveData));
	    $values = implode(',', array_values($saveData));
	    $sql = "INSERT INTO $table ($columns) VALUES ($values)";
	    $result = mysqli_query($this->conn, $sql);
	    if ($result) {
		$insertedId = mysqli_insert_id($this->conn);
	    }
	    return $insertedId;
	}
	return false;
    }
    
    /**
     * This function used to insert data in creditcards table
     * @params array 
     * @return bool
     */
    function insertCreditCards($data = NULL) {
	$table = 'credit_cards';
	$saveData = array();
	foreach ($data as $key => $val) {
	    if ($val != '') {
		$saveData[$key] = '"' . $val . '"';
	    }
	}
	if (!empty($saveData)) {
	    $columns = implode(',', array_keys($saveData));
	    $values = implode(',', array_values($saveData));
	    $sql = "INSERT INTO $table ($columns) VALUES ($values)"; // echo $sql;die;
	    $result = mysqli_query($this->conn, $sql);
	    if ($result) {
		$insertedId = mysqli_insert_id($this->conn);
	    }
	    return $insertedId;
	}
	return false;
    }
    
     /**
     * This function used to insert data in transactions table
     * @params array 
     * @return bool
     */
    function insertTransactions($data = NULL) {
	$table = 'transactions';
	$saveData = array();
	foreach ($data as $key => $val) {
	    if ($val != '') {
		$saveData[$key] = '"' . $val . '"';
	    }
	}
	if (!empty($saveData)) {
	    $columns = implode(',', array_keys($saveData));
	    $values = implode(',', array_values($saveData));
	    $sql = "INSERT INTO $table ($columns) VALUES ($values)"; //  echo $sql;die;
	    $result = mysqli_query($this->conn, $sql);
	    if ($result) {
		$insertedId = mysqli_insert_id($this->conn);
	    }
	    return $insertedId;
	}
	return false;
    }
    
    /**
     * This function used to insert data in ratings table
     * @params array 
     * @return bool
     */
    function insertRatings($data = NULL) {
	$table = 'ratings';
	$saveData = array();
	foreach ($data as $key => $val) {
	    if ($val != '') {
		$saveData[$key] = '"' . $val . '"';
	    }
	}
	if (!empty($saveData)) {
	    $columns = implode(',', array_keys($saveData));
	    $values = implode(',', array_values($saveData));
	    $sql = "INSERT INTO $table ($columns) VALUES ($values)"; // echo $sql;die;
	    $result = mysqli_query($this->conn, $sql);
	    if ($result) {
		$insertedId = mysqli_insert_id($this->conn);
	    }
	    return $insertedId;
	}
	return false;
    }
    
     /**
     * This function used to insert data in bank details table
     * @params array 
     * @return bool
     */
    function insertBankDetails($data = NULL) {
	$table = 'bank_details';
	$saveData = array();
	foreach ($data as $key => $val) {
	    if ($val != '') {
		$saveData[$key] = '"' . $val . '"';
	    }
	}
	if (!empty($saveData)) {
	    $columns = implode(',', array_keys($saveData));
	    $values = implode(',', array_values($saveData));
	    $sql = "INSERT INTO $table ($columns) VALUES ($values)"; 
	    $result = mysqli_query($this->conn, $sql);
	    if ($result) {
		$insertedId = mysqli_insert_id($this->conn);
	    }
	    return $insertedId;
	}
	return false;
    }
    
}    
?>    