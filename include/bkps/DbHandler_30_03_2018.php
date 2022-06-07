<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
/**
 * @author Manoj Sharma
 */
use Braintree\Configuration;
class DbHandler {

    private $conn;

    function __construct() {
	require_once dirname(__FILE__) . '/DbConnect.php';
	// opening db connection
	$db = new DbConnect();
	$this->conn = $db->connect();
	
/*	require_once dirname(__FILE__) . '/DbInsert.php';
	// opening db connection
	$this->dbinsert = new DbInsert();
	
	require_once dirname(__FILE__) . '/DbUpdate.php';
	// opening db connection
	$this->dbupdate = new DbUpdate(); */

	$this->runCronJob();
	$this->runCronJobDaily();
	
 	$this->logs();
    }

    /**
     * [getSqlFirstArr description]
     * @param  [string] $query [sql query]
     * @return [array]        [return first key from multi array]
     */
    private function getSqlFirstArr($query=NULL) {
    	$res = mysql_query($query);
    	$result = array();
    	if (mysql_num_rows($res) > 0) {
    		while ($array = mysql_fetch_assoc($res)) {
    			$result[] = $array;
    		}
    	}
    	return reset($result);
    }

    /**
     * [getSqlArr description]
     * @param  [string] $query [sql query]
     * @return [array]        [return a multi array]
     */
    private function getSqlArr($query=NULL) {
    	$res = mysql_query($query);
    	$result = array();
    	if (mysql_num_rows($res) > 0) {
    		while ($array = mysql_fetch_assoc($res)) {
    			$result[] = $array;
    		}
    	}
    	return $result;
    }

    /**
	 * To customize message based on selected language
		@param String $language Language Code
		@param String $message_const Message Constant
	 */
	function getMessage($language, $message_const) {
		$const = $message_const."_MSG";
		$const = $language==DEFAULT_LANGUAGE?$const:$const.'_'.strtoupper($language);
		
		if(defined($const))
			return constant($const);
		else
			return constant($message_const."_MSG");
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

	$res = mysql_query($sql);

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

	$res = mysql_query($sql);

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

	$res = mysql_query($sql);

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

	$res = mysql_query($sql);

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

	$res = mysql_query($sql);

	return $res;
    }
    
     /**
     * This function used to update data in driver details table
     * @params array $data update data 
     * @params int $int id primary key
     * @return boolean
     */
 /*   function updateDriverDetails($data = NULL, $id = 0) {
	$table = 'driver_details';
	$setPart = array();
	$bindings = array();

	foreach ($data as $key => $value) {
	    if ($value != '') {
		$setPart[] = "$key = '$value'";
	    }
	}

	$sql = "UPDATE {$table} SET " . implode(', ', $setPart) . " WHERE id = $id";

	$res = mysql_query($this->conn, $sql);

	return $res;
    } */
    
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

	$res = mysql_query($sql);

	return $res;
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
	    $result = mysql_query($sql);
	    if ($result) {
		$insertedId = mysql_insert_id();
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
	    $result = mysql_query($sql);
	    if ($result) {
		    $insertedId = mysql_insert_id();
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
	    $result = mysql_query($sql);
	    if ($result) {
		$insertedId = mysql_insert_id();
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
	    $result = mysql_query($sql);
	    if ($result) {
		$insertedId = mysql_insert_id();
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
	    $result = mysql_query($sql);
	    if ($result) {
		$insertedId = mysql_insert_id();
	    }
	    return $insertedId;
	}
	return false;
    }

    #################### Private Functions Start ##########################
    
    /**
     * This function used make logs of push notifications
     * @params $params array 
     * @return boolean 
     */
    private function logs() {
	
	$url = (!empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
	$userName = (!empty($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : ''); 
	$password = (!empty($_SERVER['PHP_AUTH_PW']) ? md5($_SERVER['PHP_AUTH_PW']) : ''); 
	
	if(strpos($url, 'get_near_by_drivers') === false && strpos($url, 'update_user_location') === false){
	    $get = print_r($_GET, true);
	    $post = "\n";
	    foreach($_POST as $key=>$value){
		$post .= "$key:$value\n";
	    }
    //	$post = print_r($_POST, true);
	    $msg = ' URL ' . $url . PHP_EOL .' Username ' . $userName . PHP_EOL .' Password ' . $password . PHP_EOL .' GET ' . $get . PHP_EOL . ' POST ' . $post . PHP_EOL   ;
	    $filename = APIPATH . 'v1/logs.txt';
	    $myfile = fopen($filename, "a") or die("Unable to open file!");
	    $txt = "<!---------------------[" . date("Y/m/d h:i:s") . "]----------------------->" . PHP_EOL . $msg . '<!------------------------------End-------------------------------->' . PHP_EOL ;
	    fwrite($myfile, $txt);
	    fclose($myfile);
	}
	return false;
    }
    
      /**
     * This function used make logs of payments 
     * @params $params array 
     * @return boolean 
     */
    private function paymentlogs($bookingId=NULL,$input=NULL,$output=NULL) {
	$url = $_SERVER['REQUEST_URI'];
	$input = print_r($input, true);
	$output = print_r($output, true);
	$msg = ' BOOKING ID ' . $bookingId . PHP_EOL .' PAYMENT INPUT ' . $input . PHP_EOL.' PAYMENT OUTPUT ' . $output . PHP_EOL   ;
	$filename = APIPATH . 'v1/paymentlogs.txt';
	$myfile = fopen($filename, "a") or die("Unable to open file!");
	$txt = "<!---------------------[" . date("Y/m/d h:i:s") . "]----------------------->" . PHP_EOL . $msg . '<!------------------------------End-------------------------------->' . PHP_EOL ;
	fwrite($myfile, $txt);
	fclose($myfile);
	return false;
    }

    private function generateRandomPassword($length) {
	$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
	for ($i = 0; $i <= $length; $i++) {
	    $num = rand(0, strlen($characters) - 1);
	    $output[] = $characters[$num];
	}
	return implode($output);
    }

    /**
     * Function to get the global setting of the site
     */
    private function getGlobalSetting() {
	$query = "SELECT * FROM global_settings LIMIT 1";
	$result = mysql_query($query);
	return mysql_fetch_assoc($result);
    }

    /**
     * This function check the value duplicacy in the users table by providing field name dynamically
     * @param string $field name of field in the users table
     * @param string $value
     * @param string $user_id
     * @return bool
     */
    private function checkUserFieldUnique($field, $value, $user_id = '') {

	$query = "SELECT id FROM users WHERE {$field} = '{$value}'";

	if ('' != $user_id) {
	    $query .= " AND id != '{$user_id}'";
	}

	$query .= ' LIMIT 1';

	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);
	return $num_rows > 0;
    }

    /**
     * This function check the value duplicacy in the vehicles table by providing field name dynamically
     * @param string $field name of field in table
     * @param string $value value of field in table
     * @param string $id primary key of table
     * @return boolean
     */
    private function checkVehiclesFieldUnique($field, $value, $id = '') {
	$table = 'vehicles';

	$query = "SELECT id FROM $table WHERE {$field} = '{$value}'";

	if ('' != $id) {
	    $query .= " AND id != '{$id}'";
	}

	$query .= ' LIMIT 1';

	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);
	return $num_rows > 0;
    }
    
     /**
     * This function check the value duplicacy in the driver details table by providing field name dynamically
     * @param string $field name of field in table
     * @param string $value value of field in table
     * @param string $id primary key of table
     * @return boolean
     */
    private function checkDriverDetailsFieldUnique($field, $value, $id = '') {
	$table = 'driver_details';

	$query = "SELECT id FROM $table WHERE {$field} = '{$value}'"; //echo $query;die;

	if ('' != $id) {
	    $query .= " AND id != '{$id}'";
	}

	$query .= ' LIMIT 1';

	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);
	return $num_rows > 0;
    }

    private function resize($image_name, $width, $height = '', $folder_name, $thumb_folder) {

	$file_extension = $this->getFileExtension($image_name);
	switch ($file_extension) {
	    case 'jpg':
	    case 'jpeg':
		$image_src = imagecreatefromjpeg($folder_name . $image_name);
		break;
	    case 'png':
	//	$image_src = imagecreatefrompng($folder_name . $image_name);
		$image_src = imagecreatefromstring(file_get_contents($folder_name . $image_name));
		break;
	    case 'gif':
		$image_src = imagecreatefromgif($folder_name . $image_name);
		break;
	}
	$true_width = imagesx($image_src);
	$true_height = imagesy($image_src);

	if ($true_width > $true_height) {
	    $height = ($true_height * $width) / $true_width;
	} else {
	    if ($height == '')
		$height = ($true_height * $width) / $true_width;

	    $width = ($true_width * $height) / $true_height;
	}
	$image_des = imagecreatetruecolor($width, $height);

	if ($file_extension == 'png') {
	    $nWidth = intval($true_width / 4);
	    $nHeight = intval($true_height / 4);
	    imagealphablending($image_des, false);
	    imagesavealpha($image_des, true);
	    $transparent = imagecolorallocatealpha($image_des, 255, 255, 255, 127);
	    imagefilledrectangle($image_des, 0, 0, $nWidth, $nHeight, $transparent);
	}

	imagecopyresampled($image_des, $image_src, 0, 0, 0, 0, $width, $height, $true_width, $true_height);

	switch ($file_extension) {
	    case 'jpg':
	    case 'jpeg':
		imagejpeg($image_des, $thumb_folder . $image_name, 100);
		break;
	    case 'png':
		imagepng($image_des, $thumb_folder . $image_name, 5);
		break;
	    case 'gif':
		imagegif($image_des, $thumb_folder . $image_name, 100);
		break;
	}
	return $image_des;
    }

    private function getFileExtension($file) {
	$extension = pathinfo($file, PATHINFO_EXTENSION);
	$extension = strtolower($extension);
	return $extension;
    }

    /**
     * This function check the value duplicacy in the driver_details table by providing field name dynamically
     * @param string $field name of field in the driver_details table
     * @param string $value
     * @param string $user_id
     * @return bool
     */
    private function checkDriverDetailFieldUnique($field, $value, $user_id = '') {

	$query = "SELECT id FROM driver_details WHERE {$field} = '{$value}'";

	if ('' != $user_id) {
	    $query .= " AND user_id != '{$user_id}'";
	}

	$query .= ' LIMIT 1';

	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);
	return $num_rows > 0;
    }

    /**
     * This function used to send booking request to near by driver
     * @params array booking_id booking id 
     * @return bool
     */
    private function sendBookingRequestToNearByDriver($params = NULL) {

	$bookingId = $params['booking_id'];

	$select_request = "SELECT * FROM bookings WHERE id = '{$bookingId}'";
	$booking_res = mysql_query($select_request);
	$booking = mysql_fetch_assoc($booking_res);

	if (!empty($booking)) {

	    $bookingId = $booking['id'];
	    $userId = $booking['user_id'];
	    $lat = $booking['pickup_lat'];
	    $long = $booking['pickup_long'];
	    $vehicleTypeId = $booking['vehicle_type_id'];
	    $currTime = time();

	    $notDriverIds = array($userId);

	    $selectBookingRequest = "SELECT * FROM booking_requests WHERE booking_id = '{$bookingId}' AND status = 2 ";
	    $bookingRequestResult = mysql_query($selectBookingRequest);
	    if (mysql_num_rows($bookingRequestResult) > 0) {
		while ($bookingRequestData = mysql_fetch_assoc($bookingRequestResult)) {
		    $notDriverIds[] = $bookingRequestData['driver_id'];
		}
	    }

	    $notDriverIds = array_unique($notDriverIds);
	    $driverDetails = $this->getNearByDriversDetails(array('user_id' => $userId, 'pickup_lat' => $lat, 'pickup_long' => $long, 'vehicle_type_id' => $vehicleTypeId, 'not_driver_ids' => $notDriverIds));

	    if (!empty($driverDetails)) {
		$driverId = $driverDetails['id'];
		$saveBookingRequest = array('booking_id' => $bookingId, 'user_id' => $userId, 'driver_id' => $driverId, 'status' => 0, 'created' => $currTime);
		$insertedId = $this->insertBookingRequestTemps($saveBookingRequest);
		if (!empty($insertedId)) {
			$this->sendBookingPush(array('id' => $insertedId));
		    return $insertedId;
		}
	    }else{
		$this->sendBookingRequestRejectUserPush(array('id'=>$bookingId));
		$updateBookingData = array('booking_status'=>3,'status'=>2,'modified'=>$currTime);
		$updateResult = $this->updateBookings($updateBookingData,$bookingId);
	    }
	}
	return false;
    }

    /**
     * This function used to get listing of near by driver
     * @params array user_id user id,pickup_lat pick up latitude,pickup_long pick up longitute,vehicle_type_id vechicle type id
     * @return array
     */
    private function getNearByDriversDetails($params = NULL) {

	$userId = $params['user_id'];
	$lat = $params['pickup_lat'];
	$long = $params['pickup_long'];
	$vehicleTypeId = $params['vehicle_type_id'];
	$currTime = time();
	$langCode = DEFAULT_LANGUAGE;
	$notDriverIds = $params['not_driver_ids'];
	$notDriverCommaIds = implode(',', $notDriverIds);

	$query = "SELECT 
		                users.id,users.first_name,users.last_name,users.photo,driver_details.driving_license_no,users.lat,
		                users.long,vehicles.id as vehicle_id,vehicles.plate_no,vehicles.color,vehicle_types.image as vehicle_type_image,
		                vehicle_type_locales.name as vehicle_type_name,
		                ( 3959 * acos( cos( radians(" . $lat . ") ) * cos( radians(users.lat ) ) 
		                * cos( radians(users.long) - radians(" . $long . ")) + sin(radians(" . $lat . ")) 
		                * sin( radians(users.lat))))*1.60934 AS distance
		                FROM 
				vehicles
				LEFT JOIN driver_details
				ON vehicles.id = driver_details.vehicle_id
		                LEFT JOIN users
		                ON driver_details.user_id = users.id
		                LEFT JOIN vehicle_types
		                ON vehicles.vehicle_type_id = vehicle_types.id
		                LEFT JOIN vehicle_type_locales
		                ON vehicle_types.id = vehicle_type_locales.vehicle_type_id AND vehicle_type_locales.lang_code='$langCode'    
		                LEFT JOIN categories
		                ON vehicle_types.category_id = categories.id        
		                LEFT JOIN category_locales
		                ON categories.id = category_locales.category_id AND category_locales.lang_code='$langCode'
		                WHERE users.driver_status = 'A'
		                AND users.user_type IN ('D','B')	
		                AND users.id NOT IN ($notDriverCommaIds)
		                AND users.lat IS NOT NULL
		                AND users.long IS NOT NULL
		                AND vehicles.is_deleted = 'N'
		                AND vehicle_types.status = 'A'
		                AND categories.status = 'A'
				AND vehicles.vehicle_type_id = $vehicleTypeId
		                LIMIT 1"; // echo $query;die;

	$res = mysql_query($query);

	$driverDetails = array();
	if (mysql_num_rows($res) > 0) {
	    $driverDetails = mysql_fetch_assoc($res);
	}
	return $driverDetails;
    }

    /**
     * This function used to save location in booking location table
     * @params array booking_address json containing booking address
     * @return bool
     */
    private function saveBookingLocation($data = NULL) {
	$bookingAddressJson = $data['booking_address'];
	$bookingId = $data['booking_id'];
	if (!empty($bookingAddressJson)) {
	    $bookingAddress = array();
	    $bookingAddress = json_decode($bookingAddressJson, true);
	    foreach ($bookingAddress as $bAddress) {
		$data = array();
		$sourceCompanyName = $bAddress['source_company_name'];
		$sourceAddress = $bAddress['source_address'];
		$sourceLat = $bAddress['source_lat'];
		$sourceLong = $bAddress['source_long'];
		$destinationCompanyName = $bAddress['destination_company_name'];
		$destinationAddress = $bAddress['destination_address'];
		$destinationLat = $bAddress['destination_lat'];
		$destinationLong = $bAddress['destination_long'];
		$currTime = time();
		$saveBookingLocationsSql = "INSERT INTO booking_locations (booking_id,source_company_name, source_address, source_lat, source_long, destination_company_name, destination_address, destination_lat, destination_long, created ) VALUES ('$bookingId','$sourceCompanyName', '$sourceAddress', '$sourceLat', '$sourceLong', '$destinationCompanyName','$destinationAddress', '$destinationLat', '$destinationLong' , '$currTime' )";
		$result = mysql_query($saveBookingLocationsSql);
	    }
	    if (!empty($result)) {
	// Note: Not need to calculate because mobile is calculating miles 	
	//	$this->calculateBookingLocationDistance(array('booking_id' => $bookingId));
		return true;
	    }
	}
	return false;
    }
    
    /**
     * This function used to save vechile in vechile table
     * @params array vehicle json containing vehicle
     * @return bool
     */
    private function saveDriverVechiles($data = NULL) {
	$vehicleJson = $data['vehicle'];
	$driverId = $data['driver_id'];
	if (!empty($vehicleJson)) {
	    $vehicles = json_decode($vehicleJson, true);
	    foreach ($vehicles as $vdata) {
		$vehicleTypeId = (!empty($vdata['vehicle_type_id']) ? $vdata['vehicle_type_id'] : '');
		$makeId = (!empty($vdata['make_id']) ? $vdata['make_id'] : '');
		$modelId = (!empty($vdata['model_id']) ? $vdata['model_id'] : '');
		$makeYear = (!empty($vdata['make_year']) ? $vdata['make_year'] : '');
		$color = (!empty($vdata['color']) ? $vdata['color'] : '');
		$plateNo = (!empty($vdata['plate_no']) ? $vdata['plate_no'] : '');
		$driverId = (!empty($vdata['driver_id']) ? $vdata['driver_id'] : '');
		$currTime = time();

		$saveVehicles = array('user_id' => $driverId,
		    'vehicle_type_id' => $vehicleTypeId,
		    'make_id' => $makeId,
		    'model_id' => $modelId,
		    'make_year' => $makeYear,
		    'color' => $color,
		    'plate_no' => $plateNo,
		    'created' => $currTime,
		    'modified' => $currTime,
		);
		$vehicleId = $this->insertVehicles($saveVehicles);
	    }
	    if (!empty($vehicleId)) {
		return true;
	    }
	}
	return false;
    }
    
     /**
     * This function used to save question in driver_question table
     * @params array question json containing question data
     * @return bool
     */
    private function saveDriverQuestions($data = NULL) {
	$questionJson = $data['question'];
	$driverId = $data['driver_id'];
	if (!empty($questionJson)) {
	    $question = json_decode($questionJson, true);
	    foreach ($question as $qdata) {
		$question = (!empty($qdata['question']) ? $qdata['question'] : '');
		$answer = (!empty($qdata['answer']) ? $qdata['answer'] : '');
		$currTime = time();
		$saveVehicles = array('question' => $question,
		    'answer' => $answer,
		    'driver_id' => $driverId,
		    'created' => $currTime,
		    'modified' => $currTime,
		);
		$insertedId = $this->insertDriverQuestions($saveVehicles);
	    }
	    if (!empty($insertedId)) {
		return true;
	    }
	}
	return false;
    }
    
    /**
     * This function used to save cargo in booking location table
     * @params array cargo_type_id json containing cargo_type_id
     * @return bool
     */
    private function saveBookingCargo($data = NULL) {
	$bookingCargoJson = $data['cargo_type_id'];
	$bookingId = $data['booking_id'];
	if (!empty($bookingCargoJson)) {
	    $bookingCargo = array();
	    $bookingCargo = json_decode($bookingCargoJson, true);
	    foreach ($bookingCargo as $key=>$value) {
		$data = array();
		$cargoTypeId = $value;
		$currTime = time();
		$saveBookingCargoSql = "INSERT INTO booking_cargo_types (booking_id,cargo_type_id,created ) VALUES ('$bookingId','$cargoTypeId', '$currTime' )";
		$result = mysql_query($saveBookingCargoSql);
	    }
	    if (!empty($result)) {
		return true;
	    }
	}
	return false;
    }
    
    /**
     * This function used to save delivery in booking table
     * @params array delivery_type_id json containing booking delivery_type_id 
     * @return bool
     */
    private function saveBookingDelivery($data = NULL) {
	$bookingDeliveryJson = $data['delivery_type_id'];
	$bookingId = $data['booking_id'];
	if (!empty($bookingDeliveryJson)) {
	    $bookingDelivery = array();
	    $bookingDelivery = json_decode($bookingDeliveryJson, true);
	    foreach ($bookingDelivery as $key=>$value) {
		$data = array();
		$deliveryTypeId = $value;
		$currTime = time();
		$saveBookingDeliverySql = "INSERT INTO booking_delivery_types (booking_id,delivery_type_id,created ) VALUES ('$bookingId','$deliveryTypeId', '$currTime' )";
		$result = mysql_query($saveBookingDeliverySql);
	    }
	    if (!empty($result)) {
		return true;
	    }
	}
	return false;
    }

    /**
     * This function used to insert data in bookings table
     * @params array 
     * @return bool
     */
    private function insertBookings($data = NULL) {
	$saveData = array();
	foreach ($data as $key => $val) {
	    if ($val != '') {
		$saveData[$key] = "'$val'";
	    }
	}
	if (!empty($saveData)) {
	    $columns = implode(',', array_keys($saveData));
	    $values = implode(',', array_values($saveData));
	    $sql = "INSERT INTO bookings ($columns) VALUES ($values)"; // echo $sql;die;
	    $result = mysql_query($sql);
	    if ($result) {
		$insertedId = mysql_insert_id();
	    }
	    return $insertedId;
	}
	return false;
    }

    /**
     * This function used to insert data in feedback_requests table
     * @params array 
     * @return bool
     */
    private function insertFeedbackRequests($data = NULL) {
	$saveData = array();
	foreach ($data as $key => $val) {
	    if ($val != '') {
		$saveData[$key] = '"' . $val . '"';
	    }
	}
	if (!empty($saveData)) {
	    $columns = implode(',', array_keys($saveData));
	    $values = implode(',', array_values($saveData));
	    $sql = "INSERT INTO feedback_requests ($columns) VALUES ($values)";
	    $result = mysql_query($sql);
	    if ($result) {
		$insertedId = mysql_insert_id();
		return $insertedId;
	    }
	}
	return false;
    }

    /**
     * This function used to insert data in booking_request_temps table
     * @params array 
     * @return bool
     */
    private function insertBookingRequestTemps($data = NULL) {
	$saveData = array();
	foreach ($data as $key => $val) {
	    if ($val != '') {
		$saveData[$key] = '"' . $val . '"';
	    }
	}
	if (!empty($saveData)) {
	    $columns = implode(',', array_keys($saveData));
	    $values = implode(',', array_values($saveData));
	    $sql = "INSERT INTO booking_request_temps ($columns) VALUES ($values)";
	    $result = mysql_query($sql);
	    if ($result) {
		$insertedId = mysql_insert_id();
	    }
	    return $insertedId;
	}
	return false;
    }
    
     /**
     * This function used to insert data in driver_questions table
     * @params array 
     * @return bool
     */
    private function insertDriverQuestions($data = NULL) {
	$table = 'driver_questions';
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
	    $result = mysql_query($sql);
	    
//	    pr($result);die;
	    
	    if ($result) {
		$insertedId = mysql_insert_id();
	    }
	    return $insertedId;
	}
	return false;
    }
    
     /**
     * This function used to insert data in driver_details table
     * @params array 
     * @return bool
     */
    private function insertDriverDetails($data = NULL) {
	$table = 'driver_details';
	$saveData = array();
	foreach ($data as $key => $val) {
	    if ($val != '') {
		$saveData[$key] = '"' . $val . '"';
	    }
	}
	if (!empty($saveData)) {
	    $columns = implode(',', array_keys($saveData));
	    $values = implode(',', array_values($saveData));
	    $sql = "INSERT INTO $table ($columns) VALUES ($values)"; //echo $sql;die;
	    $result = mysql_query($sql);
	    $insertedId = 0;
	    if ($result) {
		$insertedId = mysql_insert_id();
	    }
	    return $insertedId;
	}
	return false;
    }

    /**
     * This function used to user data in users table
     * @params array $data update data 
     * @params int $int id primary key
     * @return boolean
     */
    private function updateUsers($data = NULL, $id = 0) {
	$table = 'users';
	$setPart = array();
	$bindings = array();

	foreach ($data as $key => $value) {
	    if ($value != '') {
		$setPart[] = "$key = '$value'";
	    }
	}

	$sql = "UPDATE {$table} SET " . implode(', ', $setPart) . " WHERE id = $id"; // echo $sql;die;

	$res = mysql_query($sql);

	return $res;
    }
    
    /**
     * This function used to update data in driver_details table
     * @params array $data update data 
     * @params int $int id primary key
     * @return boolean
     */
    private function updateDriverDetails($data = NULL, $id = 0) {
	$table = 'driver_details';
	$setPart = array();
	$bindings = array();

	foreach ($data as $key => $value) {
	    if ($value != '') {
		$setPart[] = "$key = '$value'";
	    }
	}

	$sql = "UPDATE {$table} SET " . implode(', ', $setPart) . " WHERE id = $id"; // echo $sql;

	$res = mysql_query($sql);

	return $res;
    }

    /**
     * This function used to insert data in vehicles table
     * @params array 
     * @return bool
     */
    private function insertVehicles($data = NULL) {
	$table = 'vehicles';
	$saveData = array();
	foreach ($data as $key => $val) {
	    if ($val != '') {
		$saveData[$key] = '"' . $val . '"';
	    }
	}
	if (!empty($saveData)) {
	    $saveData['created'] = time();
	    $saveData['modified'] = time();
	    $columns = implode(',', array_keys($saveData));
	    $values = implode(',', array_values($saveData));
	    $sql = "INSERT INTO $table ($columns) VALUES ($values)"; // echo $sql;die;
	    $result = mysql_query($sql);
	    if ($result) {
		$insertedId = mysql_insert_id();
		return $insertedId;
	    }
	}
	return false;
    }

    /**
     * This function used to update data in vehicles table
     * @params array $data update data 
     * @params int $int id primary key
     * @return boolean
     */
    private function updateVehicles($data = NULL, $id = 0) {
	$table = 'vehicles';
	$setPart = array();
	$bindings = array();

	foreach ($data as $key => $value) {
	    if ($value != '') {
		$setPart[] = "$key = '$value'";
	    }
	}

	$sql = "UPDATE {$table} SET " . implode(', ', $setPart) . " WHERE id = $id";

	$res = mysql_query($sql);

	return $res;
    }

    /**
     * This function used to get booking locations for bookings
     * @params array 
     * @return json
     */
    private function getBookingLocationsJson($params = NULL) {
	$bookingId = $params['booking_id'];
	$query = "SELECT *  from booking_locations as bl WHERE bl.booking_id = '$bookingId' ";
	$res = mysql_query($query);
	$bookingLocations = array();
	if (mysql_num_rows($res) > 0) {
	    while ($array = mysql_fetch_assoc($res)) {
		$bookingLocations[] = array(
		    'source_company_name' => $array['source_company_name'],
		    'source_address' => $array['source_address'],
		    'source_lat' => $array['source_lat'],
		    'source_long' => $array['source_long'],
		    'destination_company_name' => $array['destination_company_name'],
		    'destination_address' => $array['destination_address'],
		    'destination_lat' => $array['destination_lat'],
		    'destination_long' => $array['destination_long'],
		    'distance' => $array['distance'],
		);
	    }
	}
	$jsonBookingLocations = json_encode($bookingLocations);
	return $jsonBookingLocations;
    }
	
	/**
     * This function used to get booking locations for bookings
     * @params array 
     * @return array
     */
    private function getBookingLocationsArr($params = NULL) {
		$bookingId = $params['booking_id'];
		$query = "SELECT *  from booking_locations as bl WHERE bl.booking_id = '$bookingId' ";
		$res = mysql_query($query);
		$bookingLocations = array();
		if (mysql_num_rows($res) > 0) {
			while ($array = mysql_fetch_assoc($res)) {
			$bookingLocations[] = array(
				'booking_location_id' => $array['id'],
				'source_company_name' => $array['source_company_name'],
				'source_address' => $array['source_address'],
				'source_lat' => $array['source_lat'],
				'source_long' => $array['source_long'],
				'destination_company_name' => $array['destination_company_name'],
				'destination_address' => $array['destination_address'],
				'destination_lat' => $array['destination_lat'],
				'destination_long' => $array['destination_long'],
				'distance' => $array['distance'],
			);
			}
		}
		return $bookingLocations;
    }
    
    /**
     * This function used to get booking cargo types for bookings
     * @params array 
     * @return array
     */
    private function getBookingCargoTypesArr($params = NULL) {
	$bookingId = $params['booking_id'];
	$langCode = (!empty($params['lang_code']) ? $params['lang_code'] : DEFAULT_LANGUAGE);
	$query = "SELECT ctl.name
			    FROM booking_cargo_types bct
			    LEFT JOIN cargo_type_locales AS ctl ON (bct.cargo_type_id = ctl.cargo_type_id AND ctl.lang_code = '$langCode')
			    WHERE bct.booking_id = '$bookingId';"; // echo $query;die;
	$res = mysql_query($query);
	$bookingCargoTypes = array();
	if (mysql_num_rows($res) > 0) {
	    while ($array = mysql_fetch_assoc($res)) {
		$bookingCargoTypes[] = array(
		    'name' => $array['name'],
		);
	    }
	}
	return $bookingCargoTypes;
    }
    
    /**
     * This function used to get booking delivery types for bookings
     * @params array 
     * @return array
     */
    private function getBookingDeliveryTypesArr($params = NULL) {  
	$bookingId = $params['booking_id'];
	$langCode = (!empty($params['lang_code']) ? $params['lang_code'] : DEFAULT_LANGUAGE);
	$query = "SELECT dtl.name
			    FROM booking_delivery_types bdt
			    LEFT JOIN delivery_type_locales AS dtl ON (bdt.delivery_type_id = dtl.delivery_type_id AND dtl.lang_code = '$langCode')
			    WHERE bdt.booking_id = '$bookingId';"; //echo $query;die;
	$res = mysql_query($query);
	$bookingDeliveryTypes = array();
	if (mysql_num_rows($res) > 0) {
	    while ($array = mysql_fetch_assoc($res)) {
		$bookingDeliveryTypes[] = array(
		    'name' => $array['name'],
		);
	    }
	}
	return $bookingDeliveryTypes;
    }
    
     /**
     * This function used to get booking cargo types for bookings
     * @params array 
     * @return array
     */
    private function getFareCategoryTypesArr($params = NULL) {
//	$bookingId = $params['booking_id'];
	$langCode = (!empty($params['lang_code']) ? $params['lang_code'] : DEFAULT_LANGUAGE);
	$query = "SELECT cl.name,c.minimum_fare,c.id
			    FROM categories c
			    LEFT JOIN category_locales AS cl ON (c.id = cl.category_id AND cl.lang_code = '$langCode')"; //  echo $query;die;  
	$res = mysql_query($query);
	$categories = array();
	if (mysql_num_rows($res) > 0) {
	    while ($array = mysql_fetch_assoc($res)) {
		$categories[] = array(
		    'id' => $array['id'],
		    'name' => $array['name'],
		    'minimum_fare' => $array['minimum_fare'],
		);
	    }
	}
	return $categories;
    }

    /**
     * This routine calculates the distance between two points (given the latitude/longitude of those points). It is being used to calculate the distance between two locations
     * @param lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)
     * @param lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)
     * @param  unit = the unit you desire for results  where: 'M' is statute miles (default)  'K' is kilometers  'N' is nautical miles              
     *         distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
     * @return float
     */
    private function distance($lat1, $lon1, $lat2, $lon2, $unit) {
	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);

	if ($unit == "K") {
	    return ($miles * 1.609344);
	} else if ($unit == "N") {
	    return ($miles * 0.8684);
	} else {
	    return $miles;
	}
    }

    /**
     * This function used to calculate total distance for booking locations
     * @params array booking_id  
     * @return int
     */
    private function calculateBookingLocationDistance($params = NULL) {
	$bookingId = $params['booking_id'];
	$query = "SELECT *  from booking_locations as bl WHERE bl.booking_id = '$bookingId' ";
	$res = mysql_query($query);
	$totalMiles = 0;
	$bookingLocations = array();
	if (mysql_num_rows($res) > 0) {
	    while ($array = mysql_fetch_assoc($res)) {
		$sourcelat = $array['source_lat'];
		$sourcelon = $array['source_long'];
		$desLat = $array['destination_lat'];
		$desLon = $array['destination_long'];
		$totalMiles = $totalMiles + $this->distance($sourcelat, $sourcelon, $desLat, $desLon, 'M');
	    }
	    
	    /*
	    $bookingLocations = array();
	    while ($array = mysql_fetch_assoc($res)) {
		$bookingLocations[] = $array;
	    }
	    $pickUpDistance = 0;
	    $desDistance = 0;
	    if (count($bookingLocations) > 1) {
		for ($i = 1; $i < count($bookingLocations); $i++) {
		    $lat1 = $bookingLocations[$i - 1]['source_lat'];
		    $lon1 = $bookingLocations[$i - 1]['source_long'];
		    $lat2 = $bookingLocations[$i]['source_lat'];
		    $lon2 = $bookingLocations[$i]['source_long'];
		    $pickUpDistance = $pickUpDistance + $this->distance($lat1, $lon1, $lat2, $lon2, 'M');

		    $desLat1 = $bookingLocations[$i - 1]['destination_lat'];
		    $desLon1 = $bookingLocations[$i - 1]['destination_long'];
		    $desLat2 = $bookingLocations[$i]['destination_lat'];
		    $desLon2 = $bookingLocations[$i]['destination_long'];
		    $desDistance = $desDistance + $this->distance($desLat1, $desLon1, $desLat2, $desLon2, 'M');
		}
	    }
	    $pickUpDesLat1 = $bookingLocations[count($bookingLocations) - 1]['source_lat'];
	    $pickUpDesLon1 = $bookingLocations[count($bookingLocations) - 1]['source_long'];
	    $pickUpDesLat2 = $bookingLocations[0]['destination_lat'];
	    $pickUpDesLon2 = $bookingLocations[0]['destination_long'];
	    $pickUpDesDistance = $this->distance($pickUpDesLat1, $pickUpDesLon1, $pickUpDesLat2, $pickUpDesLon2, 'M');
	    $totalMiles = $pickUpDistance + $desDistance + $pickUpDesDistance;
	     */
	}
	$updateBookingLocationsSql = "UPDATE bookings SET total_miles = '{$totalMiles}' WHERE id='{$bookingId}'";
	$updatedId = mysql_query($updateBookingLocationsSql);
	return $updatedId;
    }

    /**
     * This function used to automatically reject the pending booking request
     * @params 
     * @return boolean
     */
    private function autoRejectPendingBookingRequests() {
	$cronJobDurationPendingRequest = CRON_JOB_DURATION_PENDING_REQUEST;
	$query = "SELECT  *  FROM `booking_request_temps`  WHERE FROM_UNIXTIME(created) < DATE_SUB(NOW(), INTERVAL $cronJobDurationPendingRequest MINUTE)";
	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {
	    while ($booking = mysql_fetch_assoc($res)) {
		$bookingRequestTempId = $booking['id'];
		$bookingId = $booking['booking_id'];
		$userId = $booking['user_id'];
		$driverId = $booking['driver_id'];
		$rejectReason = 'Auto Rejected';
		$currTime = time();

		$saveBookingRequest = "INSERT INTO booking_requests (booking_id,user_id, driver_id, reject_reason , status, created)
									   VALUES ('$bookingId','$userId', '$driverId', '$rejectReason', 2 ,'$currTime')"; // echo $saveBookingRequest;die;
		$bookingRequestResult = mysql_query($saveBookingRequest);

		if ($bookingRequestResult) {
		    $bookingRequestInsertedId = mysql_insert_id();
		    if (!empty($bookingRequestInsertedId)) {
			$deleteRequest = "DELETE FROM booking_request_temps WHERE id = '{$bookingRequestTempId}'";
			$deleteResult = mysql_query($deleteRequest);
			if ($deleteResult == true) {
				$this->sendBookingRejectAdminEmail($bookingId,$driverId);
			    $bookingRequestParams = array('booking_id' => $bookingId);
			    $nearByDriverResults = $this->sendBookingRequestToNearByDriver($bookingRequestParams);
			    if ($nearByDriverResults) {
//								return true;	
			    }
			}
		    }
		}
	    }
	}
	return false;
    }
    
     /**
     * This function used to send booking request for schedule bookings
     * @params 
     * @return boolean
     */
    private function sendRequestForScheduleBooking() {
	$cronJobDuration = CRON_JOB_DURATION_BOOKING_SCHEDULE_REQUEST;
	$query = "SELECT *
		    FROM `bookings`
		    WHERE `run_cron_job_assign` = 'N' AND `booking_status` = '1' AND `booking_type` = '2' AND
		    pickup_date < DATE_ADD(NOW(), INTERVAL $cronJobDuration MINUTE)
		    ORDER BY id DESC"; //echo $query;die;
	$res = mysql_query($query);
	
	$currTime = time();

	if (mysql_num_rows($res) > 0) {
	    while ($booking = mysql_fetch_assoc($res)) {
	//	print_r($booking);
		
		$bookingId = $booking['id'];
		$userId = $booking['user_id'];
		$lat = $booking['pickup_lat'];
		$long = $booking['pickup_long'];
		$vehicleTypeId = $booking['vehicle_type_id'];
		$notDriverIds = array($userId);
		
		$updateBookingData = array('run_cron_job_assign'=>'Y');
		$updateResult = $this->updateBookings($updateBookingData,$bookingId);
		
		$driverDetails = $this->getNearByDriversDetails(array('user_id' => $userId, 'pickup_lat' => $lat, 'pickup_long' => $long, 'vehicle_type_id' => $vehicleTypeId, 'not_driver_ids' => $notDriverIds));
		
		//print_r($driverDetails);
		
		if (!empty($driverDetails)) {
		    $driverId = $driverDetails['id'];
		    $saveBookingRequest = "INSERT INTO booking_request_temps (booking_id,user_id, driver_id, status, created) 
										   VALUES ('$bookingId','$userId', '$driverId', 0, '$currTime')"; 
		//    echo $saveBookingRequest;
		    $result = mysql_query($saveBookingRequest);
		    if ($result) {
			$inserted_id = mysql_insert_id();
			//echo $inserted_id;
			$this->sendBookingPush(array('id' => $inserted_id));
			if (!empty($inserted_id)) {
			    $output[0]['booking_id'] = $bookingId;
			    return $output;
			    //return SUCCESSFULLY_DONE;
			}
		    }
		}
	    }
	}
	return false;
    }
    
     /**
     * This function used to auto logout driver
     * @params 
     * @return boolean
     */
    private function autoLogoutDriver() {
	$cronJobDurationAutoLogout = CRON_JOB_DURATION_AUTO_LOGOUT_DRIVER;
	$query = "SELECT `driver_details`.`id` AS `driver_detail_id` "
		. "FROM `users` LEFT JOIN `driver_details` ON `users`.`id` = `driver_details`.`user_id`"
		. " WHERE FROM_UNIXTIME(last_login) < DATE_SUB(NOW(), INTERVAL $cronJobDurationAutoLogout MINUTE)"
		. " AND `driver_details`.`vehicle_id` IS NOT NULL"
		. " AND `driver_details`.`vehicle_id` != 0";
	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {
	    while ($driverDetails = mysql_fetch_assoc($res)) {
		$driverDetailId = $driverDetails['driver_detail_id'];
		$isUpdated = $this->updateDriverDetails(array('is_on_duty' => 'N','is_online' => 'N','vehicle_id'=>'0'), $driverDetailId);
	    }
	}
	return false;
    }
    
    

    /**
     * This function used send push notification
     * @params $data array $pushNotiyType int type of push notification
     * @return boolean
     */
    private function pushNotification($data = NULL, $pushNotiyType = NULL, $lang_code=DEFAULT_LANGUAGE) { 
	if ($pushNotiyType == BOOKING_REQUEST_DRIVER) {
	    foreach ($data as $pdata) { // print_r($pdata);die;
		if ($pdata['device_type'] == 'I') {
		    $iphonePushData = array();
		    $iphonePushData = $pdata;
		    //$iphonePushData['push_msg'] = 'You have new booking request';
			$iphonePushData['push_msg'] = $this->getMessage($lang_code, "NEW_BOOKING_REQUEST");
		    $iphonePushData['push_type'] = BOOKING_REQUEST_DRIVER;
		      $deviceToken = $pdata['device_token'];
		      $result = array();
		      $result = $this->sendNotificationIphone($iphonePushData,$deviceToken);
		      $log = array();
		      $log['request'] = $result['request'];
		      $log['response'] = $result['response'];
		      $this->pushNotificationLogs($log); 
		} elseif ($pdata['device_type'] == 'A') {
		    $andriodPushData = array();
		    $andriodPushData = $pdata;
		    $andriodPushData['push_msg'] = $this->getMessage($lang_code, "NEW_BOOKING_REQUEST");
		    $andriodPushData['push_type'] = BOOKING_REQUEST_DRIVER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationAndroid($andriodPushData, $deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log);
		}
	    }
	}elseif($pushNotiyType == BOOKING_REQUEST_ACCEPT_USER){
	    foreach ($data as $pdata) {  
		if ($pdata['device_type'] == 'I') { 
		    $iphonePushData = array();
		    $iphonePushData = $pdata;
		    //$iphonePushData['push_msg'] = 'Driver has accepted the booking request';
		    $iphonePushData['push_msg'] = $this->getMessage($lang_code, "DRIVER_ACCEPT_BOOKING_REQUEST");
		    $iphonePushData['push_type'] = BOOKING_REQUEST_ACCEPT_USER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationIphone($iphonePushData,$deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log); 
		} elseif ($pdata['device_type'] == 'A') {
		    $andriodPushData = array();
		    $andriodPushData = $pdata;
		    $andriodPushData['push_msg'] = $this->getMessage($lang_code, "DRIVER_ACCEPT_BOOKING_REQUEST");
		    $andriodPushData['push_type'] = BOOKING_REQUEST_ACCEPT_USER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationAndroid($andriodPushData, $deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log);
		}
	    }
	}elseif($pushNotiyType == BOOKING_LOCATION_SOURCE_USER){
	    foreach ($data as $pdata) {  
		if ($pdata['device_type'] == 'I') { 
		    $iphonePushData = array();
		    $iphonePushData = $pdata;
		    $sourceAddress = $pdata['source_address'];
		    //$iphonePushData['push_msg'] = "Driver has picked up from $sourceAddress";
		    $iphonePushData['push_msg'] = $this->getMessage($lang_code, "DRIVER_PICKED_REQUEST");
		    $iphonePushData['push_type'] = BOOKING_LOCATION_SOURCE_USER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationIphone($iphonePushData,$deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log); 
		} elseif ($pdata['device_type'] == 'A') {
		    $andriodPushData = array();
		    $andriodPushData = $pdata;
		    $sourceAddress = $pdata['source_address'];
		    $andriodPushData['push_msg'] = $this->getMessage($lang_code, "DRIVER_PICKED_REQUEST");
		    $andriodPushData['push_type'] = BOOKING_LOCATION_SOURCE_USER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationAndroid($andriodPushData, $deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log);
		}
	    }
	}elseif($pushNotiyType == BOOKING_LOCATION_DESTINATION_USER){
	    foreach ($data as $pdata) {  
		if ($pdata['device_type'] == 'I') { 
		    $iphonePushData = array();
		    $iphonePushData = $pdata;
		    $destinationAddress = $pdata['destination_address'];
		    //$iphonePushData['push_msg'] = "Driver has drop off to $destinationAddress";
			$iphonePushData['push_msg'] = $this->getMessage($lang_code, "DRIVER_DROP_OFF").$destinationAddress;
		    $iphonePushData['push_type'] = BOOKING_LOCATION_DESTINATION_USER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationIphone($iphonePushData,$deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log); 
		} elseif ($pdata['device_type'] == 'A') {
		    $andriodPushData = array();
		    $andriodPushData = $pdata;
		    $destinationAddress = $pdata['destination_address'];
		    $andriodPushData['push_msg'] = $this->getMessage($lang_code, "DRIVER_DROP_OFF").$destinationAddress;
		    $andriodPushData['push_type'] = BOOKING_LOCATION_DESTINATION_USER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationAndroid($andriodPushData, $deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log);
		}
	    }
	}elseif($pushNotiyType == BOOKING_REQUEST_REJECT_USER){
	    foreach ($data as $pdata) {  
		if ($pdata['device_type'] == 'I') { 
		    $iphonePushData = array();
		    $iphonePushData = $pdata;
		    //$iphonePushData['push_msg'] = 'No driver is available for your last booking';
		    $iphonePushData['push_msg'] = $this->getMessage($lang_code, "NO_DRIVER_AVAILABLE");
		    $iphonePushData['push_type'] = BOOKING_REQUEST_REJECT_USER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationIphone($iphonePushData,$deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log); 
		} elseif ($pdata['device_type'] == 'A') {
		    $andriodPushData = array();
		    $andriodPushData = $pdata;
		    $andriodPushData['push_msg'] = $this->getMessage($lang_code, "NO_DRIVER_AVAILABLE");
		    $andriodPushData['push_type'] = BOOKING_REQUEST_REJECT_USER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationAndroid($andriodPushData, $deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log);
		}
	    }
	}elseif($pushNotiyType == BOOKING_PAYMENT_NOTIFY_USER){
	    foreach ($data as $pdata) {  
		if ($pdata['device_type'] == 'I') { 
		    $iphonePushData = array();
		    $iphonePushData = $pdata;
		    $price = $pdata['price'];
		    //$iphonePushData['push_msg'] = "Your credit card has been charged for amount of $price for booking";
		    $iphonePushData['push_msg'] = str_replace('{price}', $price, $this->getMessage($lang_code, "CREDIT_CARD_CHARGED"));
		    $iphonePushData['push_type'] = BOOKING_PAYMENT_NOTIFY_USER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationIphone($iphonePushData,$deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log); 
		} elseif ($pdata['device_type'] == 'A') {
		    $andriodPushData = array();
		    $andriodPushData = $pdata;
		    $price = $pdata['price'];
		    //$andriodPushData['push_msg'] = "Your credit card has been charged for amount of $price for booking";
		    $andriodPushData['push_msg'] = str_replace('{price}', $price, $this->getMessage($lang_code, "CREDIT_CARD_CHARGED"));
			$andriodPushData['push_type'] = BOOKING_PAYMENT_NOTIFY_USER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationAndroid($andriodPushData, $deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log);
		}
	    }
	}elseif($pushNotiyType == BOOKING_PAYMENT_NOTIFY_DRIVER){
	    foreach ($data as $pdata) {  
		if ($pdata['device_type'] == 'I') { 
		    $iphonePushData = array();
		    $iphonePushData = $pdata;
		    $price = $pdata['price'];
		    //$iphonePushData['push_msg'] = "Your have received an amount of $price for booking";
		    $iphonePushData['push_msg'] = str_replace('{price}', $price, $this->getMessage($lang_code, "AMOUNT_RECEIVED"));
		    $iphonePushData['push_type'] = BOOKING_PAYMENT_NOTIFY_DRIVER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationIphone($iphonePushData,$deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log); 
		} elseif ($pdata['device_type'] == 'A') {
		    $andriodPushData = array();
		    $andriodPushData = $pdata;
		    $price = $pdata['price'];
		    $andriodPushData['push_msg'] = str_replace('{price}', $price, $this->getMessage($lang_code, "AMOUNT_RECEIVED"));
		    $andriodPushData['push_type'] = BOOKING_PAYMENT_NOTIFY_DRIVER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationAndroid($andriodPushData, $deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log);
		}
	    } 
	}elseif($pushNotiyType == BOOKING_CANCEL_NOTIFY_DRIVER){
	    foreach ($data as $pdata) {  
		if ($pdata['device_type'] == 'I') { 
		    $iphonePushData = array();
		    $iphonePushData = $pdata;
		    //$iphonePushData['push_msg'] = "User has cancelled booking";
		    $iphonePushData['push_msg'] = $this->getMessage($lang_code, "USER_CANCEL_BOOKING");
		    $iphonePushData['push_type'] = BOOKING_CANCEL_NOTIFY_DRIVER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationIphone($iphonePushData,$deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log); 
		} elseif ($pdata['device_type'] == 'A') {
		    $andriodPushData = array();
		    $andriodPushData = $pdata;
		    $andriodPushData['push_msg'] = $this->getMessage($lang_code, "USER_CANCEL_BOOKING");
		    $andriodPushData['push_type'] = BOOKING_CANCEL_NOTIFY_DRIVER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationAndroid($andriodPushData, $deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log);
		}
	    }
	}elseif($pushNotiyType == ALREADY_LOGIN_NOTIFY_USER_DRIVER){
	    foreach ($data as $pdata) {  
		if ($pdata['device_type'] == 'I') { 
		    $iphonePushData = array();
		    $iphonePushData = $pdata;
		    //$iphonePushData['push_msg'] = "You had login from other device";
		    $iphonePushData['push_msg'] = $this->getMessage($lang_code, "LOGIN_OTHER_DEVICE");
		    $iphonePushData['push_type'] = ALREADY_LOGIN_NOTIFY_USER_DRIVER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationIphone($iphonePushData,$deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log); 
		} elseif ($pdata['device_type'] == 'A') {
		    $andriodPushData = array();
		    $andriodPushData = $pdata;
		    $andriodPushData['push_msg'] = $this->getMessage($lang_code, "LOGIN_OTHER_DEVICE");
		    $andriodPushData['push_type'] = ALREADY_LOGIN_NOTIFY_USER_DRIVER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationAndroid($andriodPushData, $deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log);
		}
	    }
	}elseif($pushNotiyType == NAVIGATE_REACHED_DRIVER){
	    foreach ($data as $pdata) {  
		if ($pdata['device_type'] == 'I') { 
		    $iphonePushData = array();
		    $iphonePushData = $pdata;
		    $destination_address = $pdata['destination_address'];
		    //$iphonePushData['push_msg'] = "Driver has picked up from $sourceAddress";
		    $iphonePushData['push_msg'] = $this->getMessage($lang_code, "DRIVER_DROP_OFF").$destination_address;
		    $iphonePushData['push_type'] = NAVIGATE_REACHED_DRIVER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationIphone($iphonePushData,$deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log); 
		} elseif ($pdata['device_type'] == 'A') {
		    $andriodPushData = array();
		    $andriodPushData = $pdata;
		    $destination_address = $pdata['destination_address'];
		    $andriodPushData['push_msg'] = $this->getMessage($lang_code, "DRIVER_DROP_OFF").$destination_address;
		    $andriodPushData['push_type'] = NAVIGATE_REACHED_DRIVER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationAndroid($andriodPushData, $deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log);
		}
	    }
	}elseif($pushNotiyType == PICKUP_REACHED_DRIVER){
	    foreach ($data as $pdata) {  
		if ($pdata['device_type'] == 'I') { 
		    $iphonePushData = array();
		    $iphonePushData = $pdata;
		    $destination_address = $pdata['destination_address'];
		    //$iphonePushData['push_msg'] = "Driver has picked up from $sourceAddress";
		    $iphonePushData['push_msg'] = $this->getMessage($lang_code, "DRIVER_PICKUP");
		    $iphonePushData['push_type'] = PICKUP_REACHED_DRIVER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationIphone($iphonePushData,$deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log); 
		} elseif ($pdata['device_type'] == 'A') {
		    $andriodPushData = array();
		    $andriodPushData = $pdata;
		    $destination_address = $pdata['destination_address'];
		    $andriodPushData['push_msg'] = $this->getMessage($lang_code, "DRIVER_PICKUP");
		    $andriodPushData['push_type'] = PICKUP_REACHED_DRIVER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationAndroid($andriodPushData, $deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log);
		}
	    }
	}elseif($pushNotiyType == NAVIGATE_START_DRIVER){
	    foreach ($data as $pdata) {  
		if ($pdata['device_type'] == 'I') { 
		    $iphonePushData = array();
		    $iphonePushData = $pdata;
		    $destination_address = $pdata['destination_address'];
		    //$iphonePushData['push_msg'] = "Driver has picked up from $sourceAddress";
		    $iphonePushData['push_msg'] = $this->getMessage($lang_code, "REACHED_DRIVER");
		    $iphonePushData['push_type'] = NAVIGATE_START_DRIVER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationIphone($iphonePushData,$deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log); 
		} elseif ($pdata['device_type'] == 'A') {
		    $andriodPushData = array();
		    $andriodPushData = $pdata;
		    $destination_address = $pdata['destination_address'];
		    $andriodPushData['push_msg'] = $this->getMessage($lang_code, "REACHED_DRIVER");
		    $andriodPushData['push_type'] = NAVIGATE_START_DRIVER;
		    $deviceToken = $pdata['device_token'];
		    $result = array();
		    $result = $this->sendNotificationAndroid($andriodPushData, $deviceToken);
		    $log = array();
		    $log['request'] = $result['request'];
		    $log['response'] = $result['response'];
		    $this->pushNotificationLogs($log);
		}
	    }
	}
	return false;
    }

    /**
     * This function used to send push notification to andriod devices
     * @params $andriodPushData array $device_token string device token of device
     * @return array
     */
    private function sendNotificationAndroid($andriodPushData, $deviceToken) {
	
	if (strlen($deviceToken) > 62) {

	    $title = (!empty($andriodPushData['push_msg']) ? $andriodPushData['push_msg'] : '');

	    $registrationIds = $deviceToken;
	    $registrationIds = array($registrationIds);
	    $msg = array(
		'message' => $andriodPushData,
		'title' => $title,
		'vibrate' => 1,
		'sound' => 1
	    );

	    $fields = array(
		'registration_ids' => $registrationIds,
		'data' => $msg
	    );

	    $headers = array(
		'Authorization: key=' . ANDRIOD_PUSH_KEY,
		'Content-Type: application/json'
	    );

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	    $result = curl_exec($ch); // print_r($fields); var_dump($result);die;
	    curl_close($ch);
	    // let's check the response
	    $res = array();
	    $request = array();
	    $request['fields'] = $fields;
	    $request['headers'] = $headers;
	    $res['request'] = $request;
	    $res['response'] = $result;
	    return $res;
	
	}
    }

    /**
     * This function used to send push notification to iphone devices
     * @params $iphonePushData array , $deviceToken string device token
     * @return json 
     */

    public function sendNotificationIphonePn($ip = NULL, $d = NULL) {
        $result = $this->sendNotificationIphone($ip,$d);
    	return $result;
    }

    private function sendNotificationIphone($iphonePushData, $deviceToken) { 
	$passphrase = IPHONE_PASS_PHRASE;
	$pemFile = APIPATH . 'v1/' . IPHONE_PEM_FILE;
// echo 'dsf'; die;
	if (strlen($deviceToken) > 62) {
	    $message = $iphonePushData;
//	    $message['title'] = $message['push_msg'];
	//  $deviceToken='77f754a1138408b20883577297f45cf7559620e105c6e58469209f9ab724c91b';
	    $ctx = stream_context_create();
	    stream_context_set_option($ctx, 'ssl', 'local_cert', $pemFile);
	    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
	    // Open a connection to the APNS server
	    $fp = stream_socket_client(IPHONE_PUSH_URL, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
	    if (!$fp)
		exit("Failed to connect: $err $errstr" . PHP_EOL);
	    //echo 'Connected to APNS' . PHP_EOL;
	    // Create the payload body
	    $body['aps'] = array(
		'alert' => $message['push_msg'],
		'sound' => 'default',
		'data' => $message,
	    );
	    // Encode the payload as JSON
	    $payload = json_encode($body);
	    // Build the binary notification
	    $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
	    // Send it to the server
	    $result = fwrite($fp, $msg, strlen($msg)); //print_r($result);die;
	    // Close the connection to the server
	    fclose($fp);
		
	    $res = array();
	    $request = array();
	    $res['request'] = $body;
	    $res['response'] = $result;
	    return $res;
	}
    }

    /**
     * This function used make logs of push notifications
     * @params $params array 
     * @return boolean 
     */
    private function pushNotificationLogs($params = NULL) {
	$request = print_r($params['request'], true);
	$response = print_r($params['response'], true);
	$msg = ' Request ' . $request . PHP_EOL . ' Response ' . $response . PHP_EOL;
	$filename = APIPATH . 'v1/pushlogs.txt';
	$myfile = fopen($filename, "a") or die("Unable to open file!");
	$txt = "<!---------------------[" . date("Y/m/d h:i:s") . "]----------------------->" . PHP_EOL . $msg . '<!------------------------------End-------------------------------->';
	fwrite($myfile, $txt);
	fclose($myfile);
	return false;
    }

    /**
     * This function used to send push notifications for booking request to driver
     * @params $params array 
     * @return boolean 
     */
    private function sendBookingPush($params = NULL, $lbh_dimension = NULL) {

	$id = $params['id'];
	$query = "SELECT
					  brt.id,brt.booking_id,b.price,b.pickup_lat,b.pickup_long,b.total_miles,b.price,b.dimension,b.cubicfeet,IFNULL(b.cargo_type_notes,'') cargo_type_notes,IFNULL(b.delivery_type_notes,'') delivery_type_notes,IFNULL(b.lbh_dimension,'') lbh_dimension, u.first_name,u.last_name,u.photo,u.phone,d.device_type,d.device_type,d.device_token,IFNULL(d.lang_code, '".DEFAULT_LANGUAGE."') lang_code
					FROM
					  booking_request_temps AS brt
					  LEFT JOIN users AS u
					    ON brt.user_id = u.id
					  LEFT JOIN users AS d
					    ON brt.driver_id = d.id
					  LEFT JOIN bookings AS b
					    ON brt.booking_id = b.id
					WHERE brt.id = $id AND d.device_token != '';"; //  echo $query;die;

	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {
	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {

		$image_thumb = $image_large = '';
		if ($array['photo'] != '') {
		    if (file_exists(USER_PHOTO_PATH_THUMB . $array['photo'])) {
			$image_thumb = USER_PHOTO_URL_THUMB . $array['photo'];
		    }
		    if (file_exists(USER_PHOTO_PATH_LARGE . $array['photo'])) {
			$image_large = USER_PHOTO_URL_LARGE . $array['photo'];
		    }
		}
		$bookingAddress = $this->getBookingLocationsArr(array('booking_id' => $array['booking_id']));
		
		$bookingCargoTypes = $this->getBookingCargoTypesArr(array('booking_id' => $array['booking_id'],'lang_code'=>$array['lang_code']));
		$bookingDeliveryTypes = $this->getBookingDeliveryTypesArr(array('booking_id' => $array['booking_id'],'lang_code'=>$array['lang_code']));
		$dimension = '';
		if (!empty($lbh_dimension)) {
    	/*	$dmsn = json_decode($lbh_dimension);
			$i = 0;
			foreach ($dmsn as $lbh) {
				$dimension[$i]['name'] = $lbh;
				$i++;
			} */
			$response[] = array(
			    'booking_id' => $array['booking_id'],
			    'price' => $array['price'],
			    'first_name' => $array['first_name'],
			    'last_name' => $array['last_name'],
			    'pickup_lat' => $array['pickup_lat'],
			    'pickup_long' => $array['pickup_long'],
			    'image_thumb' => $image_thumb,
			    'image_large' => $image_large,
			    'booking_address' => $bookingAddress,
			    'booking_cargo_types' => $bookingCargoTypes,
			    'booking_delivery_types' => $bookingDeliveryTypes,
			    'total_miles' => $array['total_miles'],
			    'cargo_type_notes' => $array['cargo_type_notes'],
			    'delivery_type_notes' => $array['delivery_type_notes'],
			    'price' => $array['price'],
			    'dimension' => $array['dimension'],
			    'cubicfeet' => $array['cubicfeet'],
			    'phone' => $array['phone'],
			    'device_token' => $array['device_token'],
			    'device_type' => $array['device_type'],
			    'lbh_dimension' => $array['lbh_dimension']
			);
		} else {
			$response[] = array(
			    'booking_id' => $array['booking_id'],
			    'price' => $array['price'],
			    'first_name' => $array['first_name'],
			    'last_name' => $array['last_name'],
			    'pickup_lat' => $array['pickup_lat'],
			    'pickup_long' => $array['pickup_long'],
			    'image_thumb' => $image_thumb,
			    'image_large' => $image_large,
			    'booking_address' => $bookingAddress,
			    'booking_cargo_types' => $bookingCargoTypes,
			    'booking_delivery_types' => $bookingDeliveryTypes,
			    'total_miles' => $array['total_miles'],
			    'cargo_type_notes' => $array['cargo_type_notes'],
			    'delivery_type_notes' => $array['delivery_type_notes'],
			    'price' => $array['price'],
			    'dimension' => $array['dimension'],
			    'cubicfeet' => $array['cubicfeet'],
			    'phone' => $array['phone'],
			    'device_token' => $array['device_token'],
			    'device_type' => $array['device_type']
			);

		}
		
//		echo '<pre>';
//		print_r($response);die;
		$this->pushNotification($response, BOOKING_REQUEST_DRIVER, $array['lang_code']);
	    }
	}
	return false;
    }
    
      /**
     * This function used to send push notifications for booking request to driver
     * @params $params array 
     * @return boolean 
     */
    private function sendAlreadyLoginPush($params = NULL) {

	$id = $params['id'];
	$deviceToken = $params['device_token'];
	$query = "SELECT
		    u.device_token,u.device_type,u.email,u.user_type,IFNULL(u.lang_code, '".DEFAULT_LANGUAGE."') lang_code
		  FROM
		    users AS u
		  WHERE u.device_token != '$deviceToken' AND u.id = $id;";  // echo $query;die;

	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {
	    $response = array();
	    $array = mysql_fetch_assoc($res);
	    $response[] = $array;
	    if(!empty($array['device_token'])){
		$this->pushNotification($response, ALREADY_LOGIN_NOTIFY_USER_DRIVER, $array['lang_code']);
	    }
	}
	return false;
    }
    
     /**
     * This function used to send push notifications for booking request to driver
     * @params $params array 
     * @return boolean 
     */
    public function sendBookingAcceptUserPush($params = NULL) {
	
	$langCode = (!empty($params['lang_code']) ? $params['lang_code'] : DEFAULT_LANGUAGE);

	$id = $params['id'];
	$query = "SELECT
		    b.id,UNIX_TIMESTAMP(b.pickup_date) as pickup_date,d.id as driver_id,vt.image as vehicle_type_image,d.first_name,d.last_name,d.phone,d.photo,u.device_type,u.device_token,vtl.name,v.plate_no
		  FROM
		    bookings AS b
		    LEFT JOIN users AS u
		      ON b.user_id = u.id
		    LEFT JOIN users AS d
		      ON b.driver_id = d.id
		    LEFT JOIN vehicles AS v
		      ON b.vehicle_id = v.id
		    LEFT JOIN vehicle_types AS vt
		      ON b.`vehicle_type_id` = vt.`id`  
		    LEFT JOIN vehicle_type_locales AS vtl
		      ON b.`vehicle_type_id` = vtl.`vehicle_type_id`
		      AND vtl.lang_code = '$langCode'  
		  WHERE b.id = $id AND u.device_token != '';";  // echo $query;die;

	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {
	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {

		$image_thumb = $image_large = $vehicle_type_image = '';
		if ($array['photo'] != '') {
		    if (file_exists(USER_PHOTO_PATH_THUMB . $array['photo'])) {
			$image_thumb = USER_PHOTO_URL_THUMB . $array['photo'];
		    }
		    if (file_exists(USER_PHOTO_PATH_LARGE . $array['photo'])) {
			$image_large = USER_PHOTO_URL_LARGE . $array['photo'];
		    }
		}
		
		if ($array['vehicle_type_image'] != '') {
			if (file_exists(VEHICLE_TYPE_IMG_PATH_THUMB . $array['vehicle_type_image'])) {
			   $vehicle_type_image = VEHICLE_TYPE_IMG_URL_THUMB . $array['vehicle_type_image'];
			}
		}
		//$bookingAddress = $this->getBookingLocationsArr(array('booking_id' => $array['id']));
		
		$response[] = array(
		    'booking_id' => $array['id'],
		    'first_name' => $array['first_name'],
		    'last_name' => $array['last_name'],
		    'phone' => $array['phone'],
		    'image_thumb' => $image_thumb,
		    'image_large' => $image_large,
		    'vechicle_type_name'=> $array['name'],
		    'plate_no'=> $array['plate_no'],
		    'pickup_date' => $array['pickup_date'],
		    'device_token' => $array['device_token'],
		    'device_type' => $array['device_type'],
		    'driver_id' => $array['driver_id'],
		    'vehicle_type_image' => $vehicle_type_image,
		);
		$this->pushNotification($response, BOOKING_REQUEST_ACCEPT_USER, $langCode);
	    }
	}
	return false;
    }
    
    /**
     * This function used to send push notifications for driver arrived to booking location
     * @params $params array 
     * @return boolean 
     */
    private function sendLocationPush($params = NULL, $driver=NULL) {

	$id = $params['id'];
	$type = $params['type'];
	// $query = "SELECT bl.*,u.device_token,u.device_type, IFNULL(u.lang_code, '".DEFAULT_LANGUAGE."') lang_code"
	// 	. " FROM"
	// 	. " booking_locations AS bl"
	// 	. " LEFT JOIN bookings AS b on bl.booking_id = b.id"
	// 	. " LEFT JOIN users AS u on b.user_id = u.id"
	// 	. " WHERE bl.id = $id AND u.device_token != '';";
	// if (!empty($driver)) {
	// 	$query = "SELECT bl.*,u.device_token,u.device_type, IFNULL(u.lang_code, '".DEFAULT_LANGUAGE."') lang_code"
	// 		. " FROM"
	// 		. " booking_locations AS bl"
	// 		. " LEFT JOIN bookings AS b on bl.booking_id = b.id"
	// 		. " LEFT JOIN users AS u on b.driver_id = u.id"
	// 		. " WHERE bl.id = $id AND u.device_token != '';";
	// } else {
		$query = "SELECT bl.*,u.device_token,u.device_type, IFNULL(u.lang_code, '".DEFAULT_LANGUAGE."') lang_code"
			. " FROM"
			. " booking_locations AS bl"
			. " LEFT JOIN bookings AS b on bl.booking_id = b.id"
			. " LEFT JOIN users AS u on b.user_id = u.id"
			. " WHERE bl.id = $id AND u.device_token != '';";
	// }
	
	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {
	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {
		$response[] = array(
		    'booking_id' => $array['booking_id'],
		    'source_company_name' => $array['source_company_name'],
		    'source_address' => $array['source_address'],
		    'source_lat' => $array['source_lat'],
		    'source_long' => $array['source_long'],
		    'destination_company_name' => $array['destination_company_name'],
		    'destination_address' => $array['destination_address'],
		    'destination_lat' => $array['destination_lat'],
		    'destination_long' => $array['destination_long'],
		    'device_token' => $array['device_token'],
		    'device_type' => $array['device_type'],
		);
		if($type=='S'){
			if (!empty($driver)) {
			    $this->pushNotification($response, NAVIGATE_START_DRIVER, $array['lang_code']);
			} else {
			    $this->pushNotification($response, BOOKING_LOCATION_SOURCE_USER, $array['lang_code']);
			}
		}else if($type=='D'){

			if (!empty($driver)) {
			//    $this->pushNotification($response, NAVIGATE_REACHED_DRIVER, $array['lang_code']);
			} else {
			//    $this->pushNotification($response, BOOKING_LOCATION_DESTINATION_USER, $array['lang_code']);
			}
		}else if($type=='P'){

			if (!empty($driver)) {
			    $this->pushNotification($response, PICKUP_REACHED_DRIVER, $array['lang_code']);
			} else {
			    $this->pushNotification($response, PICKUP_REACHED_DRIVER, $array['lang_code']);
			}
		}
	    }
	}
	return false;
    }
    
    /**
     * This function used to update driver arrived to booking location
     * @params $params array 
     * @return boolean 
     */
    private function updateLocation($params = NULL) {

	$id = $params['id'];
	$type = $params['type'];
	$query = "SELECT bl.source_lat,bl.source_long,bl.booking_id,bl.destination_lat,bl.destination_long"
		. " FROM"
		. " booking_locations AS bl where id=$id";  // echo $query;die;
	
	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {
	    $response = array();
	    $array = mysql_fetch_assoc($res);
	    if($type=='S'){
		if(!empty($array['source_lat']) && !empty($array['source_long']) && !empty($array['booking_id'])){
		    $sourceLat = $array['source_lat'];
		    $sourceLong = $array['source_long'];
		    $bookingId = $array['booking_id'];
		    $query = "SELECT bl.id,bl.source_lat,bl.source_long,bl.booking_id"
		    . " FROM"
		    . " booking_locations AS bl where booking_id=$bookingId AND source_lat=$sourceLat AND source_long=$sourceLong";

		    $res = mysql_query($query);

		    if (mysql_num_rows($res) > 0) {
			while ($array = mysql_fetch_assoc($res)) {
			    $bookingLocationId = $array['id'];
			    $isUpdated = $this->updateBookingLocations(array('driver_arrived_source' => 'Y'), $bookingLocationId);
			}
		    }
		}
	    }else if($type=='D'){
		if(!empty($array['destination_lat']) && !empty($array['destination_long']) && !empty($array['booking_id'])){
		    $destinationLat = $array['destination_lat'];
		    $destinationLong = $array['destination_long'];
		    $bookingId = $array['booking_id'];
		    $query = "SELECT bl.id,bl.destination_lat,bl.destination_long,bl.booking_id"
		    . " FROM"
		    . " booking_locations AS bl where booking_id=$bookingId AND destination_lat=$destinationLat AND destination_long=$destinationLong";

		    $res = mysql_query($query);

		    if (mysql_num_rows($res) > 0) {
			while ($array = mysql_fetch_assoc($res)) {
			    $bookingLocationId = $array['id'];
			    $isUpdated = $this->updateBookingLocations(array('driver_arrived_destination' => 'Y'), $bookingLocationId);
			}
		    }
		}
	    }
	    
	}
	
    }
    
    /**
     * This function used to send push notifications for user when driver reject and no driver is available 
     * @params $params array 
     * @return boolean 
     */ 
    private function sendBookingRequestRejectUserPush($params = NULL) {

	$id = $params['id'];
	$query = "SELECT b.*,u.device_token,u.device_type, IFNULL(u.lang_code, '".DEFAULT_LANGUAGE."') lang_code"
		. " FROM"
		. " bookings AS b"
		. " LEFT JOIN users AS u on b.user_id = u.id"
		. " WHERE b.id = $id AND u.device_token != '';"; // echo $query;die;
	
	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {
	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {
		$response[] = array(
		    'booking_id' => $array['id'],
		    'device_token' => $array['device_token'],
		    'device_type' => $array['device_type'],
		);
		$this->pushNotification($response, BOOKING_REQUEST_REJECT_USER, $array['lang_code']);
	    }
	}
	return false;
    }

    /**
     * This function used to get vechile details of driver
     * @params int driver_id driver id
     * @return Json
     */
    private function getDriverVechicleDetailJson($params = NULL) {

	$driverId = $params['driver_id'];
	$langCode = (!empty($params['lang_code']) ? $params['lang_code'] : DEFAULT_LANGUAGE);

	$query = "SELECT
				v.id,
				IFNULL(v.make_year, '') make_year,
				IFNULL(v.color, '') color,
				IFNULL(v.plate_no, '') plate_no,
				IFNULL(ml.name, '') make_name,
				IFNULL(mol.name, '') model_name
			    FROM
			      vehicles AS v
			     LEFT JOIN vehicle_type_locales AS vtl
				ON v.`vehicle_type_id` = vtl.`vehicle_type_id`
				AND vtl.lang_code = '$langCode'
			       LEFT JOIN make_locales AS ml
				ON v.`make_id` = ml.`make_id`
				AND ml.lang_code = '$langCode'
			      LEFT JOIN model_locales AS mol
				ON v.`model_id` = mol.`model_id`
				AND mol.lang_code = '$langCode'
			    WHERE v.user_id = $driverId AND v.is_deleted = 'N'
			    ORDER BY make_year DESC; ";

	$res = mysql_query($query);
	$vechileDetails = array();
	if (mysql_num_rows($res) > 0) {
	    while ($array = mysql_fetch_assoc($res)) {
		$vechileDetails[] = array(
		    'id' => $array['id'],
		    'make_year' => $array['make_year'],
		    'color' => $array['color'],
		    'plate_no' => $array['plate_no'],
		    'make_name' => $array['make_name'],
		    'model_name' => $array['model_name']
		);
	    }
	}
	$vechileDetailsJson = json_encode($vechileDetails);
	return $vechileDetailsJson;
    }
	
	  /**
     * This function used to get vechile details of driver
     * @params int driver_id driver id
     * @return Json
     */
    private function getDriverVechicleDetail($params = NULL) {

	$driverId = $params['driver_id'];
	$langCode = (!empty($params['lang_code']) ? $params['lang_code'] : DEFAULT_LANGUAGE);

	$query = "SELECT
			v.id,
			IFNULL(v.make_year, '') make_year,
			IFNULL(v.color, '') color,
			IFNULL(v.plate_no, '') plate_no,
			IFNULL(v.registration_doc, '') registration_doc,
			IFNULL(v.insurance_policy_doc, '') insurance_policy_doc,
			IFNULL(vt.image, '') vehicle_type_image,
			IFNULL(ml.name, '') make_name,
			IFNULL(mol.name, '') model_name,
			IFNULL(vtl.name, '') vehicle_type_name,
			IFNULL(cl.name, '') category_name
		    FROM
		      vehicles AS v
		       LEFT JOIN vehicle_types AS vt
			ON v.`vehicle_type_id` = vt.`id`
		       LEFT JOIN category_locales AS cl
			ON vt.`category_id` = cl.`category_id`
			AND cl.lang_code = '$langCode'
		       LEFT JOIN vehicle_type_locales AS vtl
			ON v.`vehicle_type_id` = vtl.`vehicle_type_id`
			AND vtl.lang_code = '$langCode'
		       LEFT JOIN make_locales AS ml
			ON v.`make_id` = ml.`make_id`
			AND ml.lang_code = '$langCode'
		      LEFT JOIN model_locales AS mol
			ON v.`model_id` = mol.`model_id`
			AND mol.lang_code = '$langCode'
		    WHERE v.user_id = $driverId AND v.is_deleted = 'N'
		    ORDER BY make_year DESC; "; // echo $query;die;
	$res = mysql_query($query);
	$vechileDetails = array();
	if (mysql_num_rows($res) > 0) {
	    while ($array = mysql_fetch_assoc($res)) {
		    $temp = array(
			'id' => $array['id'],
			'make_year' => $array['make_year'],
			'color' => $array['color'],
			'plate_no' => $array['plate_no'],
			'make_name' => $array['make_name'],
			'model_name' => $array['model_name'],
			'vehicle_type_name' => $array['vehicle_type_name'],
			'category_name' => $array['category_name'],
		    );
		
		    $temp['registration_doc'] = '';
		    $temp['insurance_policy_doc'] = '';
		    $temp['vehicle_type_image_thumb'] = '';
		    
		    if ($array['registration_doc'] != '') {
			if (file_exists(VEHICLE_DOC_PATH . $array['registration_doc'])) {
			   $temp['registration_doc'] = VEHICLE_DOC_URL . $array['registration_doc'];
			}
		    }
		    
		    if ($array['vehicle_type_image'] != '') {
			if (file_exists(VEHICLE_TYPE_IMG_PATH_THUMB . $array['vehicle_type_image'])) {
			   $temp['vehicle_type_image_thumb'] = VEHICLE_TYPE_IMG_URL_THUMB . $array['vehicle_type_image'];
			}
		    }
		    
		    if ($array['insurance_policy_doc'] != '') {
			if (file_exists(VEHICLE_DOC_PATH . $array['insurance_policy_doc'])) {
			   $temp['insurance_policy_doc'] = VEHICLE_DOC_URL . $array['insurance_policy_doc'];
			}
		    }
		    $vechileDetails[] = $temp;
	    }
	}
	return $vechileDetails;
    }

    /**
     * [sendSMTPmail send email using smtp]
     * @param  [string] $to         [to email]
     * @param  [string] $subject    [subject]
     * @param  [string] $message    [html message]
     * @param  string $senderName [sender name]
     * @return [boolean]             [response]
     */
    public function sendSMTPmail($to , $subject, $message,  $senderName=""){

    	$global_setting = $this->getGlobalSetting();
		$from_email = (!empty($global_setting['from_email']) ? $global_setting['from_email'] : 'info@pida.com');
		$from_name = (!empty($global_setting['from_email_text']) ? $global_setting['from_email_text'] : 'Admin');

//		echo PHPMAILER_LIB;die;
        
		require_once PHPMAILER_LIB;
		$mail = new PHPMailer(true);           // Passing `true` enables exceptions
		try {
			$mail->SMTPDebug = 0;              // Enable verbose debug output
			$mail->isSMTP();                   // Set mailer to use SMTP
			$mail->Host = SMTP_SERVER;  	   // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;            // Enable SMTP authentication
			$mail->Username = SMTP_USERNAME;   // SMTP username
			$mail->Password = SMTP_PASSWORD;   // SMTP password
			$mail->SMTPSecure = SMTP_SECURE;   // Enable TLS encryption, `ssl` also accepted
			$mail->Port = SMTP_PORT;           // TCP port to connect to

			//Recipients

			$mail->setFrom($from_email, $from_name);

            if(!empty($name)){
                $mail->addAddress($to, $name);     // Add a recipient    
            }else{
                $mail->addAddress($to, $to);     // Add a recipient    
            }
						
			//Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = $subject;
			$mail->Body    = $message;
			$mail->AltBody = $message;

        /*    echo '<pre>';
            print_r($mail);
            die;   */

            $this->writefile($mail,NULL);
			$mail->send();
            
		} catch (Exception $e) {
            echo '<pre>';
            print_r($e);
            die;
			return false;
		}
		return true;
	}

	/**
	 * [writefile email log file write]
	 * @param  [array] $emaildata    [email array data]
	 * @param  [array] $notification [notification array ]
	 * @return [boolean]               [response]
	 */
	public function writefile($emaildata, $notification){

        $filename = 'logs_email.txt';
		$text1 = print_r($emaildata, true);
		$text2 = print_r($notification, true);
		
        $myfile = fopen($filename, "a") or die("Unable to open file!");
        $txt = "<!---------------------[" . date("Y/m/d h:i:s") . "]----------------------->" . PHP_EOL . $text1 . PHP_EOL . $text2 . '<!------------------------------End-------------------------------->';
        fwrite($myfile, $txt);
        fclose($myfile);
        return false;
	}

    #################### Private Functions End #######################
    
    #################### Public Functions End #######################    
    public function printdata($data = NULL) {
	echo '<pre>';
	if (is_array($data)) {
	    print_r($data);
	} else {
	    echo $data;
	}
    }

    public function pr($data = NULL) {
	echo '<pre>';
	if (is_array($data)) {
	    print_r($data);
	} else {
	    echo $data;
	}
	die;
    }
    #################### Public Functions End #######################

    #################### API Functions Start #########################

    public function validateUser($email, $password) {
	$md5Password = md5($password);
	$sel_user = "SELECT id, IF(ISNULL(lang_code) OR lang_code='', '".DEFAULT_LANGUAGE."', lang_code) lang_code from users WHERE email = '$email' AND (password = '$md5Password' OR password='$password')";
	$user = mysql_query($sel_user);
	$user_id = mysql_fetch_assoc($user);
	if (mysql_num_rows($user) > 0) {
	    return $user_id;
	}
    }

    public function customerRegistration($data = array()) {

	$first_name = $data['first_name'];
	$last_name = $data['last_name'];
	$phone = $data['phone'];
	$email = $data['email'];
	$password = $data['password'];

	$curr_time = time();
	$encode_password = md5($password);
	$response = array();
	if ($this->checkUserFieldUnique('email', $email)) {
	    return 'EMAIL_ALREADY_EXISTED';
	} elseif ($this->checkUserFieldUnique('phone', $phone)) {
	    return 'PHONE_ALREADY_EXISTED';
	} else {

	    $save_user = "INSERT INTO users(first_name, last_name, phone, email, password, created, modified, user_type, customer_status, driver_status) VALUES ('$first_name', '$last_name', '$phone', '$email', '$encode_password','$curr_time', '$curr_time', 'N', 'A', 'I')";

	    $result = mysql_query($save_user);
	    if ($result) {
		$inserted_id = mysql_insert_id();

		$response['user_id'] = $inserted_id;
		$response['first_name'] = '' . $first_name;
		$response['last_name'] = '' . $last_name;
		$response['email'] = '' . $email;
		$response['phone'] = '' . $phone;

		$global_setting = $this->getGlobalSetting();
		$from_email = $global_setting['from_email_text'] . '<' . $global_setting['from_email'] . '>';

		$sub = 'Register Successfully on Pida App';
		$headers = "From: $from_email\r\n";
		$headers .= "Reply-To: \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$message = "<html><head></head><body>
				<table><tr><td> Hello " . $first_name . " " . $last_name . ", </td></tr>
				<tr><td style='padding-top: 10px;'>Your are successfully registered on Pida App. </td></tr>  
				<tr><td>Email : " . $email . " </td></tr>
				<tr><td>Password : " . $password . " </td></tr>
				<tr><td style='padding-top: 20px;'>Thanks, </td></tr>
				<tr><td>Pida App </td></tr>        
				</table>
				</body></html>";

		$this->sendSMTPmail($email, $sub, $message);

		return $response;
	    } else {
		return 'UNABLE_TO_PROCEED';
	    }
	}
	return $response;
    }

    public function validateDriverRegistration($data = array()) {

    	$first_name = $data['first_name'];
    	$last_name = $data['last_name'];
    	$phone = $data['phone'];
    	$email = $data['email'];
    	$password = $data['password'];
   
    	$response = array();

    	if ($this->checkUserFieldUnique('email', $email)) {
    		return 'EMAIL_ALREADY_EXISTED';
    	} elseif ($this->checkUserFieldUnique('phone', $phone)) {
    		return 'PHONE_ALREADY_EXISTED';
    	} else {
    		$response['user_id'] = '';
    		$response['first_name'] = $first_name;
    		$response['last_name'] = $last_name;
    		$response['email'] = $email;
    		$response['phone'] = $phone;
    		$response['password'] = $password;
    		return $response;
    	}
    	return $response;
    }	

    public function driverRegistration($data = array()) {

	$first_name = $data['first_name'];
	$last_name = $data['last_name'];
	$phone = $data['phone'];
	$email = $data['email'];
	$password = $data['password'];
//	$ssn = $data['ssn'];
//	$driving_license_no = $data['driving_license_no'];
//	$country_id = $data['country_id'];
//	$state_id = $data['state_id'];

	$curr_time = time();
	$encode_password = md5($password);
	$response = array();

	if ($this->checkUserFieldUnique('email', $email)) {
	    return 'EMAIL_ALREADY_EXISTED';
	} elseif ($this->checkUserFieldUnique('phone', $phone)) {
	    return 'PHONE_ALREADY_EXISTED';
/*	} elseif ($this->checkDriverDetailFieldUnique('ssn', $ssn)) {
	    return 'SSN_ALREADY_EXISTED';
	} elseif ($this->checkDriverDetailFieldUnique('driving_license_no', $driving_license_no)) {
	    return 'DRIVING_LICENSE_ALREADY_EXISTED'; */
	} else {


		$registerPassword = base64_encode($password);

	    $save_user = "INSERT INTO users 
							(first_name, last_name, phone, email, password, created, modified, user_type, customer_status, driver_status, register_password) 
							VALUES 
							('$first_name', '$last_name', '$phone', '$email', '$encode_password','$curr_time', '$curr_time', 'D', 'I', 'I', '$registerPassword')";

	/*	echo $save_user;
		die; */

	    $result = mysql_query($save_user);
	    if ($result) {
		$inserted_id = mysql_insert_id();

//		$save_detail = "INSERT INTO driver_details(user_id, ssn, driving_license_no, is_online, is_on_duty) VALUES ('$inserted_id', '$ssn', '$driving_license_no', 'N', 'N')";
//
//		mysql_query($this->conn, $save_detail);

		$response['user_id'] = $inserted_id;
		$response['first_name'] = '' . $first_name;
		$response['last_name'] = '' . $last_name;
		$response['email'] = '' . $email;
		$response['phone'] = '' . $phone;
//		$response['ssn'] = '' . $ssn;
//		$response['driving_license_no'] = '' . $driving_license_no;

		$global_setting = $this->getGlobalSetting();
		$from_email = $global_setting['from_email_text'] . '<' . $global_setting['from_email'] . '>';
		$admin_email = $global_setting['to_email'];

		$sub = 'Register Successfully on Pida App';
		$headers = "From: $from_email\r\n";
		$headers .= "Reply-To: \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		$variables = array();
		$variables['logo_url'] = APIURL . 'img/email_logo.png';
		$variables['driver_first_name'] = $first_name;
		$variables['driver_last_name'] = $last_name;
		$variables['driver_email'] = $email;
		$variables['driver_phone'] = $phone;
	//	print_r($variables);

	/*	$message = "<html><head></head><body>
				<table><tr><td> Hello " . $first_name . " " . $last_name . ", </td></tr>
				<tr><td style='padding-top: 10px;'>Welcome to pida family we will contact you as soon as possible or in 2 to 5 business day you will be ready to work enjoy </td></tr>  
				<tr><td style='padding-top: 20px;'>Thanks, </td></tr>
				<tr><td>Pida App </td></tr>        
				</table>
				</body></html>"; */

		$message = file_get_contents(APIPATH."v1/driver_register.html");
		foreach ($variables as $key => $value) {
		    $message = str_ireplace('{{' . $key . '}}', $value, $message);
		}	    
		
		$sub2 = 'Driver Registered on Pida App';

	/*	$message2 = "<html><head></head><body>
		<table><tr><td> Hello " . ", </td></tr>" . "<tr>
		<td style='padding-top: 10px;'> ". $first_name . " " . $last_name . ", has been successfully registered on Pida App. </td></tr>  
		<tr><td>Email : " . $email . " </td></tr>
		<tr><td style='padding-top: 20px;'>Thanks, </td></tr>
		<tr><td>Pida App </td></tr>        
		</table>
		</body></html>"; */
		
		$message2 = file_get_contents(APIPATH."v1/driver_register_admin_email.html");
		foreach ($variables as $key => $value) {
		    $message2 = str_ireplace('{{' . $key . '}}', $value, $message2);
		}	  

	/*	echo $message;
		echo $message2; 
		die; */

		$this->sendSMTPmail($email, $sub, $message);
		$this->sendSMTPmail($admin_email, $sub2, $message2);

		return $response;
	    } else {
		return 'UNABLE_TO_PROCEED';
	    }
	}
	return $response;
    }



    public function loginUser($data = array()) {

	$name = $data['name'];
	$email = $data['email'];
	$password = $data['password'];
	$login_from = $data['login_from'];
	$device_type = $data['device_type'];
	$device_token = $data['device_token'];
	$user_type = $data['user_type'];
	$lang_code = (!empty($data['lang_code']) ? $data['lang_code'] : DEFAULT_LANGUAGE);

	$login_types = array('N', 'F', 'G', 'T');
	if (in_array($login_from, $login_types)) {

	     $query = "SELECT u.*,cl.name as country_name,sl.name as state_name,ctl.name as city_name, (select count(*) from bank_details bd where bd.user_id=u.id) as totbankaccount, (select count(*) from credit_cards cc where cc.user_id=u.id) as totcards FROM users u LEFT JOIN country_locales as cl on (u.country_id = cl.country_id AND cl.lang_code = 'en') LEFT JOIN state_locales as sl on (u.state_id = sl.state_id AND sl.lang_code = 'en') LEFT JOIN city_locales as ctl on (u.city_id = ctl.city_id AND ctl.lang_code = 'en') WHERE u.email = '{$email}' LIMIT 1";
	    $chkusrlogin = mysql_query($query);
	    $datusr = mysql_fetch_assoc($chkusrlogin);
	    $curr_time = time();
	    if ('N' == $login_from) {

		if (!empty($datusr) && $datusr['password'] == md5($password)) {

		    if ('N' == $user_type && in_array($datusr['user_type'], array('N', 'B'))) {

				if ('A' == $datusr['customer_status']) {
				    
				    $params = array('id'=>$datusr['id'],'device_token'=>$device_token);
				    $this->sendAlreadyLoginPush($params);

				    $update_user = "UPDATE users SET 
	                              login_from = '{$login_from}', 
	                              device_token = '{$device_token}', 
	                              device_type = '{$device_type}',
				      lang_code = '{$lang_code}',
	                              last_login = '{$curr_time}' 
	                               WHERE email='{$email}' LIMIT 1";

				    mysql_query($update_user);
				    return $datusr;
				} else {
				    return USER_ACCOUNT_DEACTVATED;
				}
		    } elseif ('D' == $user_type && in_array($datusr['user_type'], array('D', 'B'))) {
				if ('A' == $datusr['driver_status']) {
				    
				    $params = array('id'=>$datusr['id'],'device_token'=>$device_token);
				    $this->sendAlreadyLoginPush($params);

				    $update_user = "UPDATE users SET 
	                              login_from = '{$login_from}', 
	                              device_token = '{$device_token}', 
	                              device_type = '{$device_type}',
				      lang_code = '{$lang_code}',
	                              last_login = '{$curr_time}' 
	                               WHERE email='{$email}' LIMIT 1";

				    mysql_query($update_user);

				    $query1 = "SELECT dd.*, (select count(*) from bank_details bd where bd.user_id=dd.user_id) as totbankaccount, (select count(*) from credit_cards cc where cc.user_id=dd.user_id) as totcards FROM driver_details dd WHERE dd.user_id = '{$datusr['id']}' LIMIT 1";
				    $get_data = mysql_query($query1);
				    $datusr['DriverDetail'] = mysql_fetch_assoc($get_data);

				    return $datusr;
				} else {
				    return DRIVER_ACCOUNT_DEACTVATED;
				}
		    } else {
			return INVALID_EMAIL_PASSWORD;
		    }
		} else {
		    return INVALID_EMAIL_PASSWORD;
		}
	    } else {

		if ('N' == $user_type) {
		    if (!empty($datusr)) {
			if ($datusr['customer_status'] == 'A') {
			    
			    $params = array('id'=>$datusr['id'],'device_token'=>$device_token);
			    $this->sendAlreadyLoginPush($params);
			    
			    $update_user = "UPDATE users SET device_token='{$device_token}',lang_code = '{$lang_code}', device_type = '{$device_type}', login_from = '{$login_from}', last_login = '{$curr_time}' WHERE email = '{$email}' LIMIT 1";
			    mysql_query($update_user);
			    return $datusr;
			} else {
			    return USER_ACCOUNT_DEACTVATED;
			}
		    } else {
			if ('' != $password) {
			    $npassword = $password;
			    $password = md5($password);
			} else {
//							
			    $npassword = 123456;
			    $password = md5($npassword);
			}
			$save_user = "INSERT INTO users SET first_name = '{$name}', 
												 email = '{$email}',
												 password = '{$password}',
												 device_token = '{$device_token}',
												 device_type = '{$device_type}',
												 customer_status = 'A',
												 driver_status = 'I', 												 
												 user_type = 'N', 												 
												 login_from = '{$login_from}',   
												 last_login = '{$curr_time}',   
												 created = '{$curr_time}',  
												 lang_code = '{$lang_code}',
												 modified = '{$curr_time}'";

			mysql_query($save_user);
			$insertId = mysql_insert_id();
			if ($insertId > 0) {
			    $query = "SELECT u.*,sl.name as state_name,ctl.name as city_name, (select count(*) from bank_details bd where bd.user_id=u.id) as totbankaccount, (select count(*) from credit_cards cc where cc.user_id=u.id) as totcards FROM users u LEFT JOIN country_locales as cl on (u.country_id = cl.country_id AND cl.lang_code = 'en') LEFT JOIN state_locales as sl on (u.state_id = sl.state_id AND sl.lang_code = 'en') LEFT JOIN city_locales as ctl on (u.city_id = ctl.city_id AND ctl.lang_code = 'en') WHERE u.id = '{$insertId}' LIMIT 1";
			    $usrdat = mysql_query($query);
			    if (mysql_num_rows($usrdat) > 0) {
				$datusr = mysql_fetch_assoc($usrdat);
				$msg = "Your Login Id : " . $email . " AND  Password : " . $npassword;
				$this->sendSMTPmail($email, "Pida app login detail", $msg);
				return $datusr;
			    }
			} else {
			    return UNABLE_TO_PROCEED;
			}
		    }
		} else {
		    return INVALID_EMAIL_PASSWORD;
		}
	    }
	} else {
	    return INVALID_REQUEST;
	}
    }

    public function getCategories($lang = 'en') {

	$query = "SELECT 
					category_locales.category_id, category_locales.name, category_locales.lang_code, categories.status
					FROM 
						category_locales 
					INNER JOIN 
						categories ON (category_locales.category_id = categories.id)
					WHERE 
						category_locales.lang_code = '{$lang}' 
						AND 
						categories.status = 'A' 
					ORDER BY  category_locales.name ASC";

	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {

	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {

		$response[] = array(
		    'category_id' => $array['category_id'],
		    'name' => $array['name'],
		    'lang_code' => $array['lang_code'],
		    'status' => $array['status']
		);
	    }
	    return $response;
	} else {
	    return UNABLE_TO_PROCEED;
	}
    }

    public function getVehicleTypes($lang = 'en', $category_id) {
	
	if($category_id){
	    	$query = "SELECT 
					vehicle_type_locales.vehicle_type_id, vehicle_type_locales.name, vehicle_type_locales.lang_code, vehicle_types.status, vehicle_types.image
					FROM 
						vehicle_type_locales 
					INNER JOIN 
						vehicle_types ON (vehicle_type_locales.vehicle_type_id = vehicle_types.id)
					WHERE 
						vehicle_type_locales.lang_code = '{$lang}' 
						AND 
						vehicle_types.category_id = '{$category_id}' 
						AND 
						vehicle_types.status = 'A' 
					ORDER BY  vehicle_type_locales.name ASC";
	}else{
	    	$query = "SELECT 
					vehicle_type_locales.vehicle_type_id, vehicle_type_locales.name, vehicle_type_locales.lang_code, vehicle_types.status, vehicle_types.image
					FROM 
						vehicle_type_locales 
					INNER JOIN 
						vehicle_types ON (vehicle_type_locales.vehicle_type_id = vehicle_types.id)
					WHERE 
						vehicle_type_locales.lang_code = '{$lang}' 
						AND 
						vehicle_types.status = 'A' 
					ORDER BY  vehicle_type_locales.name ASC";
	}



	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {

	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {

		$image_thumb = $image_large = '';

		if ($array['image'] != '') {
		    if (file_exists(VEHICLE_TYPE_IMG_PATH_THUMB . $array['image'])) {
			$image_thumb = VEHICLE_TYPE_IMG_URL_THUMB . $array['image'];
		    }
		    if (file_exists(VEHICLE_TYPE_IMG_PATH_LARGE . $array['image'])) {
			$image_large = VEHICLE_TYPE_IMG_URL_LARGE . $array['image'];
		    }
		}

		$response[] = array(
		    'vehicle_type_id' => $array['vehicle_type_id'],
		    'category_id' => $category_id,
		    'name' => $array['name'],
		    'lang_code' => $array['lang_code'],
		    'status' => $array['status'],
		    'image_thumb' => $image_thumb,
		    'image_large' => $image_large
		);
	    }
	    return $response;
	} else {
	    return UNABLE_TO_PROCEED;
	}
    }

    public function getCargoTypes($lang = 'en') {

	$query = "SELECT 
					cargo_type_locales.cargo_type_id, cargo_type_locales.name, cargo_type_locales.lang_code, cargo_types.status
					FROM 
						cargo_type_locales 
					INNER JOIN 
						cargo_types ON (cargo_type_locales.cargo_type_id = cargo_types.id)
					WHERE 
						cargo_type_locales.lang_code = '{$lang}' 
						AND 
						cargo_types.status = 'A' 
					ORDER BY  cargo_type_locales.name ASC";

	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {

	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {

		$response[] = array(
		    'cargo_type_id' => $array['cargo_type_id'],
		    'name' => $array['name'],
		    'lang_code' => $array['lang_code'],
		    'status' => $array['status']
		);
	    }
	    return $response;
	} else {
	    return UNABLE_TO_PROCEED;
	}
    }

    public function getCountries($lang = 'en') {
	
	$queryCountry = "SELECT country_id from country_locales where name LIKE '%united%'";
	$resCountry = mysql_query($queryCountry);

	if (mysql_num_rows($resCountry) > 0) {
	    
	    $arrayCountry = mysql_fetch_assoc($resCountry);
	    $countryId = $arrayCountry['country_id'];
	    
	    $query = "SELECT 
					country_locales.country_id, country_locales.name, country_locales.lang_code, countries.status
					FROM 
						country_locales 
					INNER JOIN 
						countries ON (country_locales.country_id = countries.id)
					WHERE 
						country_locales.lang_code = '{$lang}' 
						AND 
						countries.status = 'A' 
					ORDER BY FIELD(country_id,$countryId) DESC";
	}else{
	    
	      $query = "SELECT 
					country_locales.country_id, country_locales.name, country_locales.lang_code, countries.status
					FROM 
						country_locales 
					INNER JOIN 
						countries ON (country_locales.country_id = countries.id)
					WHERE 
						country_locales.lang_code = '{$lang}' 
						AND 
						countries.status = 'A' 
					ORDER BY  country_locales.name ASC";
	    
	}

	

	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {

	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {

		$response[] = array(
		    'country_id' => $array['country_id'],
		    'name' => $array['name'],
		    'lang_code' => $array['lang_code'],
		    'status' => $array['status']
		);
	    }
	    return $response;
	} else {
	    return UNABLE_TO_PROCEED;
	}
    }

    public function getStates($lang = 'en', $country_id) {

	$query = "SELECT 
					state_locales.state_id, state_locales.name, state_locales.lang_code, states.status, states.country_id
					FROM 
						state_locales 
					INNER JOIN 
						states ON (state_locales.state_id = states.id)
					WHERE 
						state_locales.lang_code = '{$lang}' ";

	if (0 != $country_id) {
	    $query .= " AND states.country_id = '{$country_id}' ";
	}

	$query .= " AND states.status = 'A' 
					ORDER BY  state_locales.name ASC";

	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {

	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {

		$response[] = array(
		    'state_id' => $array['state_id'],
		    'country_id' => $array['country_id'],
		    'name' => $array['name'],
		    'lang_code' => $array['lang_code'],
		    'status' => $array['status']
		);
	    }
	    return $response;
	} else {
	    return UNABLE_TO_PROCEED;
	}
    }

    public function getCities($lang = 'en', $state_id) {

	$query = "SELECT 
					city_locales.city_id, city_locales.name, city_locales.lang_code, cities.status, cities.country_id, cities.state_id
					FROM 
						city_locales 
					INNER JOIN 
						cities ON (city_locales.city_id = cities.id)
					WHERE 
						city_locales.lang_code = '{$lang}' ";

	if (0 != $state_id) {
	    $query .= " AND cities.state_id = '{$state_id}' ";
	}

	$query .= " AND cities.status = 'A' 
					ORDER BY  city_locales.name ASC";

	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {

	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {

		$response[] = array(
		    'city_id' => $array['city_id'],
		    'state_id' => $array['state_id'],
		    'country_id' => $array['country_id'],
		    'name' => $array['name'],
		    'lang_code' => $array['lang_code'],
		    'status' => $array['status']
		);
	    }
	    return $response;
	} else {
	    return UNABLE_TO_PROCEED;
	}
    }

    //send new password on forgot password
    public function sendNewPassword($email) {
	$sel_user = "SELECT * FROM users WHERE email = '$email'";
	$result = mysql_query($sel_user);
	$user = mysql_fetch_assoc($result);
	if (!empty($user)) {

	    $global_setting = $this->getGlobalSetting();
	    $from_email = $global_setting['from_email_text'] . '<' . $global_setting['from_email'] . '>';

	    $newpass = $this->generateRandomPassword(6);
	    $newpassdb = md5($newpass);
	    $update_user = "UPDATE users set password='$newpassdb' where email='$email'";
	    $result = mysql_query($update_user);

	    $sub = 'Pida App : Login Credentials';
	    $headers = "From: $from_email\r\n";
	    $headers .= "Reply-To: \r\n";
	    $headers .= "MIME-Version: 1.0\r\n";
	    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	    $message = "<html><head></head><body>
				<table><tr><td> Hello " . $user['first_name'] . " " . $user['last_name'] . ", </td></tr>
				<tr><td style='padding-top: 10px;'>Your new creadentials for login on Pida App: </td></tr>  
				<tr><td>Email : " . $email . " </td></tr>
				<tr><td>Password : " . $newpass . " </td></tr>
				<tr><td style='padding-top: 20px;'>Thanks, </td></tr>
				<tr><td>Pida App </td></tr>        
				</table>
				</body></html>";

	    $this->sendSMTPmail($email, $sub, $message);


	    if ($result) {
		return SUCCESSFULLY_DONE;
	    } else {
		return UNABLE_TO_PROCEED;
	    }
	} else {
	    return INVALID_EMAIL;
	}
    }

    public function getLanguages() {
	$query = "SELECT * FROM languages WHERE status = 'A' ORDER BY language ASC";

	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {

	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {

		$response[] = array(
		    'language_id' => $array['id'],
		    'language' => $array['language'],
		    'lang_code' => $array['lang_code'],
		    'status' => $array['status']
		);
	    }
	    return $response;
	} else {
	    return UNABLE_TO_PROCEED;
	}
    }

    //update passsword
    public function changePassword($data = array()) {

	$user_id = $data['user_id'];
	$old_password = $data['old_password'];
	$new_password = $data['new_password'];

	$select_user = "SELECT * FROM users WHERE id = '{$user_id}'";
	$user_res = mysql_query($select_user);
	$user = mysql_fetch_assoc($user_res);

	if (!empty($user)) {
	    if ($user['password'] == md5($old_password)) {

		$new_password = md5($new_password);
		$curr_time = time();
		$update_user = "UPDATE users set password = '{$new_password}', modified = '{$curr_time}' WHERE id = '{$user_id}'";
		mysql_query($update_user);
		$result = mysql_affected_rows();

		if ($result) {
		    return SUCCESSFULLY_DONE;
		} else {
		    return UNABLE_TO_PROCEED;
		}
	    } else {
		return INVALID_OLD_PASSWORD;
	    }
	} else {
	    return INVALID_USER;
	}
    }

    public function getFaq($lang = 'en') {

	$query = "SELECT 
					faq_locales.faq_id, faq_locales.question, faq_locales.answer, faq_locales.lang_code, faqs.status
					FROM 
						faq_locales 
					INNER JOIN 
						faqs ON (faq_locales.faq_id = faqs.id)
					WHERE 
						faq_locales.lang_code = '{$lang}' 
						AND 
						faqs.status = 'A' 
					ORDER BY  faq_locales.faq_id ASC";

	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {

	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {

		$response[] = array(
		    'faq_id' => $array['faq_id'],
		    'question' => $array['question'],
		    'answer' => $array['answer'],
		    'lang_code' => $array['lang_code'],
		    'status' => $array['status']
		);
	    }
	    return $response;
	} else {
	    return UNABLE_TO_PROCEED;
	}
    }

    public function getStaticPage($lang = 'en', $page_id) {

	$query = "SELECT 
					page_locales.page_id, page_locales.name, page_locales.lang_code, pages.status, page_locales.body
					FROM 
						page_locales 
					INNER JOIN 
						pages ON (page_locales.page_id = pages.id)
					WHERE 
						page_locales.lang_code = '{$lang}' 
						AND 
						page_locales.page_id = '{$page_id}' 
						AND 
						pages.status = 'A' 
					LIMIT 1";

	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {

	    $r = mysql_fetch_assoc($res);

	    $response = array(
		'page_id' => $page_id,
		'name' => $r['name'],
		//'body' => str_replace(array("\r", "\n"), '', strip_tags($r['body'])),
		'body' => $r['body'],
		'lang_code' => $r['lang_code'],
		'status' => $r['status']
	    );

	    return $response;
	} else {
	    return UNABLE_TO_PROCEED;
	}
    }

    public function getVehicleMakes($lang = 'en') {

	$query = "SELECT 
					make_locales.make_id, make_locales.name, make_locales.lang_code, makes.status
					FROM 
						make_locales 
					INNER JOIN 
						makes ON (make_locales.make_id = makes.id)
					WHERE 
						make_locales.lang_code = '{$lang}' 
						AND 
						makes.status = 'A' 
					ORDER BY  make_locales.name ASC";

	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {

	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {

		$response[] = array(
		    'make_id' => $array['make_id'],
		    'name' => $array['name'],
		    'lang_code' => $array['lang_code'],
		    'status' => $array['status']
		);
	    }
	    return $response;
	} else {
	    return UNABLE_TO_PROCEED;
	}
    }

    public function getVehicleModels($lang = 'en', $make_id) {

	$query = "SELECT 
					model_locales.model_id, model_locales.name, model_locales.lang_code, models.status, models.make_id
					FROM 
						model_locales 
					INNER JOIN 
						models ON (model_locales.model_id = models.id)
					WHERE 
						model_locales.lang_code = '{$lang}' ";

	if (0 != $make_id) {
	    $query .= " AND models.make_id = '{$make_id}' ";
	}

	$query .= " AND models.status = 'A' 
					ORDER BY  model_locales.name ASC";

	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {

	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {

		$response[] = array(
		    'model_id' => $array['model_id'],
		    'make_id' => $array['make_id'],
		    'name' => $array['name'],
		    'lang_code' => $array['lang_code'],
		    'status' => $array['status']
		);
	    }
	    return $response;
	} else {
	    return UNABLE_TO_PROCEED;
	}
    }

    public function updateUserLocation($data = array()) {
	
//	$this->runCronJob();
	$user_id = $data['user_id'];
	$lat = $data['lat'];
	$long = $data['long'];
	$booking_id = $data['booking_id'];

	$select_user = "SELECT * FROM users WHERE id = '{$user_id}'";
	$user_res = mysql_query($select_user);
	$user = mysql_fetch_assoc($user_res);

	if (!empty($user)) {

	    $curr_time = time();
	/*    $save_log = "INSERT INTO user_location_logs(`user_id`, `lat`, `long`, created, modified) VALUES ('{$user_id}', '{$lat}', '{$long}', '{$curr_time}', '{$curr_time}')";

	    mysql_query($save_log); */

	    $update_user = "UPDATE users set `lat` = '{$lat}', `long` = '{$long}', `last_login` = '{$curr_time}' WHERE id = '{$user_id}' ";
	    mysql_query($update_user);
	    $result = mysql_affected_rows();		
		
			// check booking id and append location there 
			if($booking_id) {
				$query = "select travel_route from bookings Where id='$booking_id' and booking_status='4'";
				$result = mysql_query($query);
				$array = mysql_fetch_assoc($result);
				if(!empty($array)) {
					// get old travel route
					$route = $array['travel_route']?json_decode($array['travel_route'], true):array();
					$route[] = array(
						'latitude'=>$lat,
						'longitude'=>$long
						);
					$route = json_encode($route);
					$UdateBooking = "update bookings set travel_route='$route' where id='$booking_id' driver_id='$user_id'";
					mysql_query($UdateBooking);

				}
			}
		
	    return SUCCESSFULLY_DONE;

	} else {
	    return INVALID_USER;
	}
    }

    /**
     * This function used to get listing of constants used for fare calculations
     * @params 
     * @return array 
     */
    public function getFareCalculation($params=NULL) {
	
	$lang_code = (!empty($params['lang_code']) ? $params['lang_code'] : DEFAULT_LANGUAGE);

	$globalSettings = $this->getGlobalSetting();

	if (!empty($globalSettings)) {
	    $pidaCommission = $globalSettings['admin_pida_fee'];

	    $fareCalculation = array();
	    $fareCalculation['courier'] = array(
		'pida_commission' => $pidaCommission,
		'admin_pida_fee' => $globalSettings['admin_pida_fee'],
		'per_mile_fare' => $globalSettings['per_mile_fare'],
		'minimum_fare' => $globalSettings['minimum_fare'],
		'minimum_fare_json' => $this->getFareCategoryTypesArr(array('lang_code'=>$lang_code)),
		'minimum_fare_distance' => $globalSettings['minimum_fare_distance'],
	    );

	    $fareCalculation['cargo'] = array(
		'pida_commission' => $pidaCommission,
		'admin_pida_fee' => $globalSettings['admin_pida_fee'],
	    );

	    $fareCalculation['walking'] = array(
		'pida_commission' => $pidaCommission,
		'admin_pida_fee' => $globalSettings['admin_pida_fee'],
	    );

	    $response[] = $fareCalculation;
	    return $response;
	} else {
	    return UNABLE_TO_PROCEED;
	}
    }

    /**
     * This function used to get listing of constants used for fare calculations
     * @params int user_id user id
     * @params float miles distance in miles
     * @params lat lat user current latitude
     * @params long long user current longitude
     * @return array 
     */
    public function getNearByDrivers($params = NULL) {

	$userId = $params['user_id'];
	$miles = $params['miles'];
	$lat = $params['lat'];
	$long = $params['long'];
	$lang_code = $params['lang_code'];

	$query = "SELECT 
                    users.id,users.first_name,users.last_name,users.photo,driver_details.driving_license_no,users.lat,
                    users.long,vehicles.plate_no,vehicles.color,vehicle_types.image as vehicle_type_image,
                    vehicle_type_locales.name as vehicle_type_name,vehicle_types.id as vehicle_type_id,
                     ( 3959 * acos( cos( radians(" . $lat . ") ) * cos( radians(users.lat ) ) 
                     * cos( radians(users.long) - radians(" . $long . ")) + sin(radians(" . $lat . ")) 
                     * sin( radians(users.lat))))*1.60934 AS distance
                    FROM 
                    	driver_details
                    LEFT JOIN users
                        ON driver_details.user_id = users.id
                    LEFT JOIN vehicles
                        ON driver_details.vehicle_id = vehicles.id
                    LEFT JOIN vehicle_types
                        ON vehicles.vehicle_type_id = vehicle_types.id
                    LEFT JOIN vehicle_type_locales
                        ON vehicle_types.id = vehicle_type_locales.vehicle_type_id AND vehicle_type_locales.lang_code='$lang_code'    
                    LEFT JOIN categories
                    	ON vehicle_types.category_id = categories.id        
                    LEFT JOIN category_locales
                        ON categories.id = category_locales.category_id AND category_locales.lang_code='$lang_code'
                    WHERE users.driver_status = 'A'
                    AND users.user_type IN ('D','B')	
                    AND users.id != $userId
                    AND users.lat IS NOT NULL
                    AND users.long IS NOT NULL
                    AND driver_details.is_on_duty = 'Y'
                    AND driver_details.is_online = 'Y'
                    AND vehicles.is_deleted = 'N'
                    AND vehicle_types.status = 'A'
                    AND categories.status = 'A'
                    HAVING distance < $miles";

	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {
	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {
		$temp = array();
		$temp['id'] = $array['id'];
		$temp['first_name'] = $array['first_name'];
		$temp['last_name'] = $array['last_name'];

		$temp['photo_thumb'] = $temp['photo_large'] = $temp['vehicle_type_image_thumb'] = $temp['vehicle_type_image_large'] = '';

		if ($array['photo'] != '') {
		    if (file_exists(USER_PHOTO_PATH_THUMB . $array['photo'])) {
			$temp['photo_thumb'] = USER_PHOTO_URL_THUMB . $array['photo'];
		    }
		    if (file_exists(USER_PHOTO_PATH_LARGE . $array['photo'])) {
			$temp['photo_large'] = USER_PHOTO_URL_LARGE . $array['photo'];
		    }
		}
		$temp['lat'] = $array['lat'];
		$temp['long'] = $array['long'];
		$temp['plate_no'] = $array['plate_no'];
		$temp['color'] = $array['color'];
		$temp['vehicle_type_id'] = $array['vehicle_type_id'];

		if ($array['vehicle_type_image'] != '') {
		    if (file_exists(VEHICLE_TYPE_IMG_PATH_THUMB . $array['vehicle_type_image'])) {
			$temp['vehicle_type_image_thumb'] = VEHICLE_TYPE_IMG_URL_THUMB . $array['vehicle_type_image'];
		    }
		    if (file_exists(VEHICLE_TYPE_IMG_PATH_LARGE . $array['vehicle_type_image'])) {
			$temp['vehicle_type_image_large'] = VEHICLE_TYPE_IMG_URL_LARGE . $array['vehicle_type_image'];
		    }
		}
		$temp['vehicle_type_name'] = $array['vehicle_type_name'];
		$response[] = $temp;
	    }
	    return $response;
	} else {
	    return UNABLE_TO_PROCEED;
	}
    }

    /**
     * This function used to get listing of delivery types
     * @params string $lang language code
     * @return array 
     */
    public function getDeliveryTypes($lang = DEFAULT_LANGUAGE) {

	$query = "SELECT 
						delivery_type_locales.delivery_type_id, delivery_type_locales.name as delivery_type_name, delivery_type_locales.lang_code, delivery_types.status
						FROM 
							delivery_type_locales 
						INNER JOIN 
							delivery_types ON (delivery_type_locales.delivery_type_id = delivery_types.id)
						WHERE 
							delivery_type_locales.lang_code = '{$lang}' 
							AND 
							delivery_types.status = 'A' 
						ORDER BY  delivery_type_locales.name ASC";

	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {

	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {

		$response[] = array(
		    'delivery_type_id' => $array['delivery_type_id'],
		    'delivery_type_name' => $array['delivery_type_name'],
		    'lang_code' => $array['lang_code'],
		    'status' => $array['status']
		);
	    }
	    return $response;
	} else {
	    return NO_RECORD_FOUND;
	}
    }

    /**
     * This function used to get listing of feedback types
     * @params string $lang language code
     * @return array 
     */
    public function getFeedbackTypes($lang = DEFAULT_LANGUAGE) {

	$query = "SELECT 
						feedback_type_locales.feedback_type_id, feedback_type_locales.name as feedback_type_name, feedback_type_locales.lang_code, feedback_types.status
						FROM 
							feedback_type_locales 
						INNER JOIN 
							feedback_types ON (feedback_type_locales.feedback_type_id = feedback_types.id)
						WHERE 
							feedback_type_locales.lang_code = '{$lang}' 
							AND 
							feedback_types.status = 'A' 
						ORDER BY  feedback_type_locales.created ASC";

	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {

	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {

		$response[] = array(
		    'feedback_type_id' => $array['feedback_type_id'],
		    'feedback_type_name' => $array['feedback_type_name'],
		    'lang_code' => $array['lang_code'],
		    'status' => $array['status']
		);
	    }
	    return $response;
	} else {
	    return NO_RECORD_FOUND;
	}
    }

    /**
     * This function used to book a driver 
     * @params int $user_id user id
     * @params int $lat user current latitude
     * @params int $long user current longitude
     * @params int $vehicle_type_id vehicle type id
     * @params date $pickup_date pick up date
     * @params json $booking_address json array of multiple locations [{"source_company_name":"B R Softech1","source_address":"Triven nagar","source_lat":"26.912326","source_long":"75.787253","destination_company_name":"A3 logics","destination_address":"Sitapura","destination_lat":"26.912912","destination_long":"75.797188"}]
     * @params int $cargo_type_id cargo type id
     * @params string $cargo_type_notes cargo type notes
     * @params int $delivery_type_id delivery type id
     * @params string $delivery_type_notes delivery type notes
     * @params float price ride price
     * @params float total_miles total ride miles
     * @params float dimension dimension of cargo
     * @params float cubicfeet cargo cubic feet
     * @return array 
     */
    public function bookDriver($params = NULL) {
	$output = array();
	$userId = $params['user_id'];
	$lat = $params['lat'];
	$long = $params['long'];
	$vehicleTypeId = $params['vehicle_type_id'];

	if (!empty($params['pickup_date'])) {
	    $pickupDate = date('Y-m-d H:i:s', strtotime($params['pickup_date']));
	    $bookingType = 2;
	} else {
	    $pickupDate = date('Y-m-d H:i:s');
	    $bookingType = 1;
	}
	$bookingAddress = $params['booking_address'];
	$cargoTypeId = $params['cargo_type_id'];
	$cargonTypeNotes = (!empty($params['cargo_type_notes']) ? $params['cargo_type_notes'] : '');
	$deliveryTypeId = $params['delivery_type_id'];
	$deliveryTypeNotes = (!empty($params['delivery_type_notes']) ? $params['delivery_type_notes'] : '');
	
	$price = (!empty($params['price']) ? $params['price'] : '');
	$total_miles = (!empty($params['total_miles']) ? $params['total_miles'] : '');
	$dimension = (!empty($params['dimension']) ? $params['dimension'] : '');
	$cubicfeet = (!empty($params['cubicfeet']) ? $params['cubicfeet'] : '');
	$lbhDimension = (!empty($params['lbh_dimension']) ? $params['lbh_dimension'] : '');
	
	
	$currTime = time();
	
	if($bookingType==1){
	    
	$notDriverIds = array($userId);

	$driverDetails = $this->getNearByDriversDetails(array('user_id' => $userId, 'pickup_lat' => $lat, 'pickup_long' => $long, 'vehicle_type_id' => $vehicleTypeId, 'not_driver_ids' => $notDriverIds));

	if (!empty($driverDetails)) {
	    $driverId = $driverDetails['id'];
	    $vehicleId = $driverDetails['vehicle_id'];
	    $saveBooking = array('user_id' => $userId,
	//	'vehicle_id' => $vehicleId,
		'vehicle_type_id' => $vehicleTypeId,
		'pickup_lat' => $lat,
		'pickup_long' => $long,
		'pickup_date' => $pickupDate,
		//'cargo_type_id' => $cargoTypeId,
		'cargo_type_notes' => $cargonTypeNotes,
		//'delivery_type_id' => $deliveryTypeId,
		'delivery_type_notes' => $deliveryTypeNotes,
		'booking_status' => 1,
		'booking_type' => $bookingType,
		'created' => $currTime,
		'total_miles' => $total_miles,
		'price' => $price,
		'dimension' => $dimension,
		'cubicfeet' => $cubicfeet,
		'lbh_dimension' => $lbhDimension,
	    );
	    $bookingId = $this->insertBookings($saveBooking);
	    if ($bookingId) {
		$bookingId = mysql_insert_id();
		$this->saveBookingLocation(array('booking_id' => $bookingId, 'booking_address' => $bookingAddress));
		$this->saveBookingCargo(array('booking_id' => $bookingId, 'cargo_type_id' => $cargoTypeId));
		$this->saveBookingDelivery(array('booking_id' => $bookingId, 'delivery_type_id' => $deliveryTypeId));
		$saveBookingRequest = "INSERT INTO booking_request_temps (booking_id,user_id, driver_id, status, created) 
										   VALUES ('$bookingId','$userId', '$driverId', 0, '$currTime')";
		$result = mysql_query($saveBookingRequest);
		if ($result) {
		    $inserted_id = mysql_insert_id();
		    if (!empty($params['lbh_dimension'])) {
		    	$this->sendBookingPush(array('id' => $inserted_id), $params['lbh_dimension']);
		    } else {
		    	$this->sendBookingPush(array('id' => $inserted_id));
		    }
		    if (!empty($inserted_id)) {
			$output[0]['booking_id']=$bookingId;
			return $output;
			//return SUCCESSFULLY_DONE;
		    }
		}
	    }
	}
	    
	    
	}else{
	    $saveBooking = array('user_id' => $userId,
	//	'vehicle_id' => $vehicleId,
		'vehicle_type_id' => $vehicleTypeId,
		'pickup_lat' => $lat,
		'pickup_long' => $long,
		'pickup_date' => $pickupDate,
		//'cargo_type_id' => $cargoTypeId,
		'cargo_type_notes' => $cargonTypeNotes,
		//'delivery_type_id' => $deliveryTypeId,
		'delivery_type_notes' => $deliveryTypeNotes,
		'booking_status' => 1,
		'booking_type' => $bookingType,
		'created' => $currTime,
		'total_miles' => $total_miles,
		'price' => $price,
		'dimension' => $dimension,
		'cubicfeet' => $cubicfeet,
	    );
	    $bookingId = $this->insertBookings($saveBooking);
	    if ($bookingId) {
		$bookingId = mysql_insert_id();
		$this->saveBookingLocation(array('booking_id' => $bookingId, 'booking_address' => $bookingAddress));
		$this->saveBookingCargo(array('booking_id' => $bookingId, 'cargo_type_id' => $cargoTypeId));
		$this->saveBookingDelivery(array('booking_id' => $bookingId, 'delivery_type_id' => $deliveryTypeId));
		$output[0]['booking_id']=$bookingId;
		return $output;
		
	    }
	}
	return NO_RECORD_FOUND;
    }

    /**
     * This function used to get listing of booking request
     * @params int $driver_id driver id
     * @return array 
     */
    public function getBookingRequest($params = NULL) {
	$driverId = $params['driver_id'];
	$query = "SELECT
						  brt.id,brt.booking_id,b.pickup_lat,b.pickup_long,b.total_miles,u.first_name,u.last_name,u.photo
						FROM
						  booking_request_temps AS brt
						  LEFT JOIN users AS u
						    ON brt.user_id = u.id
						  LEFT JOIN bookings AS b
						    ON brt.booking_id = b.id
						WHERE brt.driver_id = $driverId;";

	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {

	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {

		$image_thumb = $image_large = '';
		if ($array['photo'] != '') {
		    if (file_exists(USER_PHOTO_PATH_THUMB . $array['photo'])) {
			$image_thumb = USER_PHOTO_URL_THUMB . $array['photo'];
		    }
		    if (file_exists(USER_PHOTO_PATH_LARGE . $array['photo'])) {
			$image_large = USER_PHOTO_URL_LARGE . $array['photo'];
		    }
		}
		$bookingAddress = $this->getBookingLocationsArr(array('booking_id' => $array['booking_id']));

		$response[] = array(
		    'booking_id' => $array['booking_id'],
		    'first_name' => $array['first_name'],
		    'last_name' => $array['last_name'],
		    'pickup_lat' => $array['pickup_lat'],
		    'pickup_long' => $array['pickup_long'],
		    'image_thumb' => $image_thumb,
		    'image_large' => $image_large,
		    'booking_address' => $bookingAddress,
		    'total_miles' => $array['total_miles'],
		);
	    }
	    return $response;
	} else {
	    return NO_RECORD_FOUND;
	}
    }

    /**
     * This function used to accept booking request
     * @params int $driver_id driver id
     * @params int $booking_id booking id
     * @return array 
     */
    public function acceptBookingRequest($data = array()) {
	$bookingId = $data['booking_id'];
	$driverId = $data['driver_id'];

	$select_request = "SELECT brt.*,b.vehicle_type_id FROM booking_request_temps as brt LEFT JOIN bookings as b on brt.booking_id=b.id  WHERE brt.booking_id = '{$bookingId}' AND brt.driver_id = '{$driverId}'";
	$booking_res = mysql_query($select_request);
	$booking = mysql_fetch_assoc($booking_res);

	if (!empty($booking)) {
	    $bookingRequestTempId = $booking['id'];
	    $bookingId = $booking['booking_id'];
	    $userId = $booking['user_id'];
	    $driverId = $booking['driver_id'];
	    $vehicleTypeId = $booking['vehicle_type_id'];
	    $currTime = time();

	    $saveBookingRequest = "INSERT INTO booking_requests (booking_id,user_id, driver_id, status, created)
									   VALUES ('$bookingId','$userId', '$driverId', 1 ,'$currTime')";
	    $bookingRequestResult = mysql_query($saveBookingRequest);

	    if ($bookingRequestResult) {
		$bookingRequestInsertedId = mysql_insert_id();
		if (!empty($bookingRequestInsertedId)) {
		    
		    $select_vechicle = "SELECT id FROM vehicles WHERE vehicle_type_id = '{$vehicleTypeId}' AND user_id = '{$driverId}'";
		    $vechicle_res = mysql_query($select_vechicle);
		    $vechicle = mysql_fetch_assoc($vechicle_res);
		    $vechicleId = (!empty($vechicle['id']) ? $vechicle['id'] : '');
		    
		    $updateBookingData = array('driver_id'=>$driverId,'booking_status'=>2,'status'=>1,'modified'=>$currTime,'vehicle_id'=>$vechicleId);
		    $updateResult = $this->updateBookings($updateBookingData,$bookingId);
		
		    $deleteRequest = "DELETE FROM booking_request_temps WHERE booking_id = '{$bookingId}'";
		    $deleteResult = mysql_query($deleteRequest);

		    if ($updateResult == true && $deleteResult == true) {
			$this->sendBookingAcceptUserPush(array('id'=>$bookingId));
			return SUCCESSFULLY_DONE;
		    }
		}
	    } else {
		return UNABLE_TO_PROCEED;
	    }
	} else {
	    return NO_RECORD_FOUND;
	}
    }

    /**
     * This function used to reject booking request
     * @params int $driver_id driver id
     * @params int $booking_id booking id
     * @params string $reject_reason reject reason
     * @return array 
     */
    public function rejectBookingRequest($data = array()) {
	$bookingId = $data['booking_id'];
	$driverId = $data['driver_id'];
	$rejectReason = $data['reject_reason'];

	$select_request = "SELECT * FROM booking_request_temps as brt WHERE brt.booking_id = '{$bookingId}' AND brt.driver_id = '{$driverId}'";
	$booking_res = mysql_query($select_request);
	$booking = mysql_fetch_assoc($booking_res);

	if (!empty($booking)) {
	    $bookingRequestTempId = $booking['id'];
	    $bookingId = $booking['booking_id'];
	    $userId = $booking['user_id'];
	    $driverId = $booking['driver_id'];
	    $currTime = time();

	    $saveBookingRequest = "INSERT INTO booking_requests (booking_id,user_id, driver_id, reject_reason , status, created)
									   VALUES ('$bookingId','$userId', '$driverId', '$rejectReason', 2 ,'$currTime')";
	    $bookingRequestResult = mysql_query($saveBookingRequest);

	    if ($bookingRequestResult) {
		$bookingRequestInsertedId = mysql_insert_id();
		if (!empty($bookingRequestInsertedId)) {
		    $deleteRequest = "DELETE FROM booking_request_temps WHERE booking_id = '{$bookingId}'";
		    $deleteResult = mysql_query($deleteRequest);
		    if ($deleteResult == true) {
			$this->sendBookingRejectAdminEmail($bookingId,$driverId);
			$bookingRequestParams = array('booking_id' => $bookingId);
			$nearByDriverResults = $this->sendBookingRequestToNearByDriver($bookingRequestParams);
			return SUCCESSFULLY_DONE;
			/* if ($nearByDriverResults) {
			    return SUCCESSFULLY_DONE;
			} else {
			    return NO_RECORD_FOUND;
			} */
		    }
		}
	    } else {
		return UNABLE_TO_PROCEED;
	    }
	} else {
	    return NO_RECORD_FOUND;
	}
    }

    /**
     * This function used to generate feedback request
     * @params int $user_id user id
     * @params int $feedback_type_id feedback type id
     * @params string $message query message text
     * @return array 
     */
    public function feedbackRequest($params = NULL) {

	$userId = $params['user_id'];
	$message = $params['message'];
	$feedbackTypeId = $params['feedback_type_id'];
	$currTime = time();

	$saveFeedbackRequest = array('user_id' => $userId, 'feedback_type_id' => $feedbackTypeId, 'message' => $message, 'created' => $currTime);
	$requestId = $this->insertFeedbackRequests($saveFeedbackRequest);

	if ($requestId) {
	    return SUCCESSFULLY_DONE;
	}
	return UNABLE_TO_PROCEED;
    }

    /**
     * This function used to get user data
     * @params int $user_id user id
     * @return array 
     */
    public function getUser($params = NULL) {

	$userId = (!empty($params['user_id']) ? $params['user_id'] : 0);

	$query = "SELECT 
						u.first_name,u.last_name,u.photo,u.user_type						
						FROM 
							users as u
						WHERE 
							u.id = $userId";

	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {

	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {

		$image_thumb = $image_large = '';
		if ($array['photo'] != '') {
		    if (file_exists(USER_PHOTO_PATH_THUMB . $array['photo'])) {
			$image_thumb = USER_PHOTO_URL_THUMB . $array['photo'];
		    }
		    if (file_exists(USER_PHOTO_PATH_LARGE . $array['photo'])) {
			$image_large = USER_PHOTO_URL_LARGE . $array['photo'];
		    }
		}

		$response[] = array(
		    'first_name' => $array['first_name'],
		    'last_name' => $array['last_name'],
		    'user_type' => $array['user_type'],
		    'image_thumb' => $image_thumb,
		    'image_large' => $image_large,
		);
	    }
	    return $response;
	} else {
	    return NO_RECORD_FOUND;
	}
    }

    /**
     * This function used to get driver details
     * @params int $user_id user id
     * @return array 
     */
    public function getDriverDetails($params = NULL) {

	$userId = (!empty($params['user_id']) ? $params['user_id'] : 0);
	$langCode = (!empty($params['lang_code']) ? $params['lang_code'] : DEFAULT_LANGUAGE);

	$query = "SELECT
			IFNULL(d.id, '') driver_id,
			IFNULL(d.first_name, '') first_name,
			IFNULL(d.last_name, '') last_name,
			IFNULL(d.photo, '') photo,
			IFNULL(d.user_type, '') user_type,
			IFNULL(d.address, '') address,
			IFNULL(d.zip_code, '') zip_code,
			IFNULL(d.phone, '') phone,
			IFNULL(cl.country_id, '') country_id,
			IFNULL(cl.name, '') country_name,
			IFNULL(sl.state_id, '') state_id,
			IFNULL(sl.name, '') state_name,
			IFNULL(cil.city_id, '') city_id,
			IFNULL(cil.name, '') city_name
		    FROM
		      users AS d
		      LEFT JOIN country_locales AS cl
			ON cl.`country_id` = d.`country_id`
			AND cl.`lang_code` = '$langCode'
		      LEFT JOIN state_locales AS sl
			ON sl.`state_id` = d.`state_id`
			AND sl.`lang_code` = '$langCode'
		      LEFT JOIN city_locales AS cil
			ON cil.`city_id` = d.`city_id`
			AND cil.`lang_code` = '$langCode'
		    WHERE d.id = $userId
		    AND d.user_type IN ('D','B')
		    AND d.driver_status = 'A';";

	$res = mysql_query($query);

	if (mysql_num_rows($res) > 0) {

	    $response = array();
	    while ($array = mysql_fetch_assoc($res)) {

		$driverId = $array['driver_id'];
		$image_thumb = $image_large = '';
		if ($array['photo'] != '') {
		    if (file_exists(USER_PHOTO_PATH_THUMB . $array['photo'])) {
			$image_thumb = USER_PHOTO_URL_THUMB . $array['photo'];
		    }
		    if (file_exists(USER_PHOTO_PATH_LARGE . $array['photo'])) {
			$image_large = USER_PHOTO_URL_LARGE . $array['photo'];
		    }
		}

		$vehicleDetailsJson = $this->getDriverVechicleDetail(array('driver_id' => $driverId, 'lang_code' => $langCode));

		$response[] = array(
		    'first_name' => $array['first_name'],
		    'last_name' => $array['last_name'],
		    'user_type' => $array['user_type'],
		    'image_thumb' => $image_thumb,
		    'image_large' => $image_large,
		    'address' => $array['address'],
		    'zip_code' => $array['zip_code'],
		    'phone' => $array['phone'],
		    'country_id' => $array['country_id'],
		    'country_name' => $array['country_name'],
		    'state_id' => $array['state_id'],
		    'state_name' => $array['state_name'],
		    'city_id' => $array['city_id'],
		    'city_name' => $array['city_name'],
		    'vehicle_details' => $vehicleDetailsJson,
		);
	    }
	    return $response;
	} else {
	    return NO_RECORD_FOUND;
	}
    }

    /**
     * This function used for cron job functions
     * @params 
     * @return boolean 
     */
    public function runCronJob() {
	
	
//		$this->sendBookingPush(array('id'=>32)); die;
	$this->autoRejectPendingBookingRequests();
	$this->sendRequestForScheduleBooking();
	$this->autoLogoutDriver();
	return false;
    }

     /**
     * This function used for cron job functions
     * @params 
     * @return boolean 
     */
     public function runCronJobDaily() {

     	$arrivedPayment = "SELECT id FROM bookings WHERE pickup_date <= NOW() - INTERVAL 2 DAY and booking_status IN (1,4,2);";

     	$resArrived = mysql_query($arrivedPayment);

     	if (mysql_num_rows($resArrived) > 0) {
     		$response = array();
     		while ($array = mysql_fetch_assoc($resArrived)) {
     			$bookingID = $array['id'];
		       $this->updateBookings(array('booking_status'=>6),$bookingID);
     		}
     	} 
     	return false;
     }

    /**
     * This function used to update customer data
     * @params $data update data
     * @return boolean 
     */
    public function updateUser($data = NULL) {
	$userId = (!empty($data['id']) ? $data['id'] : 0);
	$firstName = (!empty($data['first_name']) ? $data['first_name'] : '');
	$lastName = (!empty($data['last_name']) ? $data['last_name'] : '');
	$email = (!empty($data['email']) ? $data['email'] : '');
	$phone = (!empty($data['phone']) ? $data['phone'] : '');
	$image = (!empty($data['photo']) ? $data['photo'] : '');
	$time = time();
	$response = array();
	$db = new DbHandler();
	$chk_user = mysql_query("select * from users where id=$userId AND user_type IN('N','B') AND customer_status = 'A' ");
	//echo "select * from users where id=$user_id"; exit;
	if (mysql_num_rows($chk_user) > 0) {
	    $pass_data = mysql_fetch_array($chk_user);

	    $select_user = "update users set first_name='$firstName', last_name='$lastName' ";

	    if ($phone != '') {
		$select_user.=", phone='$phone'";
	    }

	    if ($email != '') {
		$checkEmail = $this->checkUserFieldUnique('email', $email, $userId);
		if (!empty($checkEmail)) {
		    return EMAIL_ALREADY_EXISTED;
		}
		$select_user.=", email='$email'";
	    }

	    if ($image != '') {
		$user_image = mysql_query("select photo from users where id=" . $userId);
		$uimagedata = mysql_fetch_array($user_image);
		//echo $uimagedata['image']; exit;
		if (isset($image['name']) && $image['name'] != '') {
		    $ex1 = explode(".", $image['name']);
		    $ext = end($ex1);
		    $user_image = $userId . '-' . time() . "_flixa." . $ext;
		    if (move_uploaded_file($image['tmp_name'], USER_PHOTO_PATH . $user_image)) {
			$this->resize($user_image, 300, 240, USER_PHOTO_PATH, USER_PHOTO_PATH_LARGE);
			$this->resize($user_image, 150, 120, USER_PHOTO_PATH, USER_PHOTO_PATH_THUMB);
			if (file_exists(USER_PHOTO_PATH . $uimagedata['photo'])) {
			    @unlink(USER_PHOTO_PATH . $uimagedata['photo']);
			    @unlink(USER_PHOTO_PATH_LARGE . $uimagedata['photo']);
			    @unlink(USER_PHOTO_PATH_THUMB . $uimagedata['photo']);
			}
		    }
		}
		$select_user.=", photo='$user_image'";
	    }
	    $select_user.=", modified=$time where id=$userId";

	    $user_res = mysql_query($select_user);

	    $result = mysql_affected_rows();
	    if ($result > 0) {
		$chk_user = mysql_query("select * from users where id=" . $userId);
		$udata = mysql_fetch_array($chk_user);
		//print_r($udata); exit;
		return $udata;
	    } else {
		return UNABLE_TO_PROCEED;
	    }
	} else {
	    return INVALID_USER;
	}
    }

    /**
     * This function used to update driver data
     * @params $data update data
     * @return boolean 
     */
    public function updateDriver($data = NULL) {
	$driverId = (!empty($data['driver_id']) ? $data['driver_id'] : 0);
	$firstName = (!empty($data['first_name']) ? $data['first_name'] : '');
	$lastName = (!empty($data['last_name']) ? $data['last_name'] : '');
	$email = (!empty($data['email']) ? $data['email'] : '');
	$phone = (!empty($data['phone']) ? $data['phone'] : '');
	$image = (!empty($data['photo']) ? $data['photo'] : '');
	$address = (!empty($data['address']) ? $data['address'] : '');
	$countryId = (!empty($data['country_id']) ? $data['country_id'] : 0);
	$stateId = (!empty($data['state_id']) ? $data['state_id'] : 0);
	$cityId = (!empty($data['city_id']) ? $data['city_id'] : 0);
	$zipCode = (!empty($data['zip_code']) ? $data['zip_code'] : '');
	$dob = (!empty($data['dob']) ? $data['dob'] : '');
	$time = time();

	$response = array();
	$db = new DbHandler();
	$chk_user = mysql_query("select count(*) from users where id=$driverId AND user_type IN('D','B') AND driver_status = 'A' ");
	if (mysql_num_rows($chk_user) > 0) {
	    $pass_data = mysql_fetch_array($chk_user);

	    $updateFields = array('first_name' => $firstName,
		'last_name' => $lastName,
		'phone' => $phone,
		'address' => $address,
		'country_id' => $countryId,
		'state_id' => $stateId,
		'city_id' => $cityId,
		'zip_code' => $zipCode,
		'dob' => $dob,
	    );

	    if ($email != '') {
		$checkEmail = $this->checkUserFieldUnique('email', $email, $driverId);
		if (!empty($checkEmail)) {
		    return EMAIL_ALREADY_EXISTED;
		}
		$updateFields['email'] = $email;
	    }

	    if ($image != '') {
		$user_image = mysql_query("select photo from users where id=" . $driverId);
		$uimagedata = mysql_fetch_array($user_image);
		//echo $uimagedata['image']; exit;
		if (isset($image['name']) && $image['name'] != '') {
		    $ex1 = explode(".", $image['name']);
		    $ext = end($ex1);
		    $user_image = $driverId . '-' . time() . "_user_photo." . $ext;
		    if (move_uploaded_file($image['tmp_name'], USER_PHOTO_PATH . $user_image)) {
			$this->resize($user_image, 300, 240, USER_PHOTO_PATH, USER_PHOTO_PATH_LARGE);
			$this->resize($user_image, 150, 120, USER_PHOTO_PATH, USER_PHOTO_PATH_THUMB);
			if (file_exists(USER_PHOTO_PATH . $uimagedata['photo'])) {
			    @unlink(USER_PHOTO_PATH . $uimagedata['photo']);
			    @unlink(USER_PHOTO_PATH_LARGE . $uimagedata['photo']);
			    @unlink(USER_PHOTO_PATH_THUMB . $uimagedata['photo']);
			}
		    }
		}
		$updateFields['photo'] = $user_image;
	    }

	    $isUpdated = $this->updateUsers($updateFields, $driverId);

	    if ($isUpdated) {
		$fields = array('id',
		    'first_name',
		    'last_name',
		    'email',
		    'IFNULL(phone, \'\') phone',
		    'IFNULL(address, \'\') address',
		    'IFNULL(country_id, \'0\') country_id',
		    'IFNULL(state_id, \'0\') state_id',
		    'IFNULL(city_id, \'0\') city_id',
		    'IFNULL(zip_code, \'\') zip_code',
		    'IFNULL(photo, \'\') photo',
		    'IFNULL(dob, \'\') dob',
		);
		$fieldsSql = implode(',', $fields);
		$selectSql = "select $fieldsSql from users where id=$driverId";
		$selectDriver = mysql_query($selectSql);
		$driverData = mysql_fetch_array($selectDriver);
		return $driverData;
	    } else {
		return UNABLE_TO_PROCEED;
	    }
	} else {
	    return INVALID_USER;
	}
    }

    /**
     * This function used to save driver vechicles
     * @params int $make_id make id
     * @params int $make_year make year
     * @params int $driver_id driver id
     * @params string $color color
     * @params string $plate_no plate no
     * @return array 
     */
    public function addDriverVehicles($params = NULL) { 

	$vehicleTypeId = (!empty($params['vehicle_type_id']) ? $params['vehicle_type_id'] : '');
	$makeId = (!empty($params['make_id']) ? $params['make_id'] : '');
	$modelId = (!empty($params['model_id']) ? $params['model_id'] : '');
	$makeYear = (!empty($params['make_year']) ? $params['make_year'] : '');
	$color = (!empty($params['color']) ? $params['color'] : '');
	$plateNo = (!empty($params['plate_no']) ? $params['plate_no'] : '');
	$driverId = (!empty($params['driver_id']) ? $params['driver_id'] : '');
	$insurancePolicyDoc = (!empty($params['insurance_policy_doc']) ? $params['insurance_policy_doc'] : '');
	$registrationDoc = (!empty($params['registration_doc']) ? $params['registration_doc'] : '');
	$currTime = time();

	$saveVehicles = array('user_id' => $driverId,
	    'vehicle_type_id' => $vehicleTypeId,
	    'make_id' => $makeId,
	    'model_id' => $modelId,
	    'make_year' => $makeYear,
	    'color' => $color,
	    'plate_no' => $plateNo,
	    'created' => $currTime,
	    'modified' => $currTime,
	);
	
	if (!empty($insurancePolicyDoc)) {
	    $allowedExtension = array('doc','pdf','png','jpg','jpeg');
	    $ex1 = explode(".", $insurancePolicyDoc['name']);
	    $ext = end($ex1);
	    $fileSize = $insurancePolicyDoc['size'];
	    $maxFileSize = 2*(1024*1024);  
	    if($fileSize > $maxFileSize){ //echo 'FileSize'.$fileSize.'MaxFileSize'.$maxFileSize;die;
		 return 'INSURANCE_POLICY_DOC_SIZE';
	    }
	    if(!in_array(strtolower($ext),$allowedExtension)){
		return 'INSURANCE_POLICY_DOC_TYPE';
	    }
	    $insurance_image = $driverId . '-' . time() . "_insurance." . $ext;
	    $saveVehicles['insurance_policy_doc'] = $insurance_image;
	    if (move_uploaded_file($insurancePolicyDoc['tmp_name'], VEHICLE_DOC_PATH . $insurance_image)) {
	//	$this->resize($insurance_image, 300, 240, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_LARGE);
	//	$this->resize($insurance_image, 150, 120, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_THUMB);
	    } 
	}

	if (!empty($registrationDoc)) {
	    $allowedExtension = array('doc','pdf','png','jpg','jpeg');
	    $ex1 = explode(".", $registrationDoc['name']);
	    $ext = end($ex1);
	    $fileSize = $registrationDoc['size'];
	    $maxFileSize = 2*(1024*1024);
	    if($fileSize > $maxFileSize){
		 return 'REGISTRATION_DOC_SIZE';
	    }
	    if(!in_array(strtolower($ext),$allowedExtension)){
		return 'REGISTRATION_DOC_TYPE';
	    }
	    $registration_image = $driverId . '-' . time() . "_registration." . $ext;
	    $saveVehicles['registration_doc'] = $registration_image;
	    if (move_uploaded_file($registrationDoc['tmp_name'], VEHICLE_DOC_PATH . $registration_image)) {
	//	$this->resize($registration_image, 300, 240, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_LARGE);
	//	$this->resize($registration_image, 150, 120, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_THUMB);
	    }
	}
	
	//print_r($saveVehicles);die;
	    
	$vehicleId = $this->insertVehicles($saveVehicles);

	if ($vehicleId) {
	    return SUCCESSFULLY_DONE;
	}
	return UNABLE_TO_PROCEED;
    }

    /**
     * This function used to save driver vechicles
     * @params int $id id
     * @params int $driver_id driver id
     * @return array 
     */
    public function deleteDriverVehicle($params = NULL) {

	$id = (!empty($params['id']) ? $params['id'] : '');
	$driverId = (!empty($params['driver_id']) ? $params['driver_id'] : '');

	$vehicleSql = "SELECT * FROM vehicles WHERE id = '$id' AND user_id = '$driverId' AND is_deleted = 'N'"; // echo $vehicleSql;die;
	$vehicleResult = mysql_query($vehicleSql);
	$vehiclesData = mysql_fetch_assoc($vehicleResult);

	if ($vehiclesData) {
	    $isDeleted = $this->updateVehicles(array('is_deleted' => 'Y'), $id);
	    if ($isDeleted) {
		return SUCCESSFULLY_DONE;
	    }
	} else {
	    return NO_RECORD_FOUND;
	}
	return UNABLE_TO_PROCEED;
    }
    
    /**
     * This function used to save driver vechicles
     * @params int $driver_id driver id
     * @return array 
     */
    public function driverVehicleOnline($params = NULL) {

	$driverId = (!empty($params['driver_id']) ? $params['driver_id'] : '');
	$vehicleId = (isset($params['vehicle_id']) ? $params['vehicle_id'] : '');
	
	$vehiclesSql = "SELECT id FROM vehicles WHERE user_id = '$driverId' and id = '$vehicleId' ";  
	
	$vehiclesResult = mysql_query($vehiclesSql);
	if (mysql_num_rows($vehiclesResult) == 0) {
	        return NO_RECORD_FOUND;
	}

	$driverDetailsSql = "SELECT id FROM driver_details WHERE user_id = '$driverId'"; 
	$driverDetailsResult = mysql_query($driverDetailsSql);
	$driverDetailsData = mysql_fetch_assoc($driverDetailsResult);
   
	if ($driverDetailsData) {
	    $id = @$driverDetailsData['id'];
	    $isDeleted = $this->updateDriverDetails(array('is_on_duty' => 'Y','is_online' => 'Y','vehicle_id'=>$vehicleId), $id);
	    if ($isDeleted) {
		return SUCCESSFULLY_DONE;
	    }
	} else {
	    $inserted = $this->insertDriverDetails(array('is_on_duty' => 'Y','is_online' => 'Y','vehicle_id'=>$vehicleId,'user_id'=>$driverId));
	    return SUCCESSFULLY_DONE;
	}
	return UNABLE_TO_PROCEED;
    }
    
      /**
     * This function used to update driver vechicles
     * @params int $driver_id driver id
     * @return array 
     */
    public function driverVehicleOffline($params = NULL) {

	$driverId = (!empty($params['driver_id']) ? $params['driver_id'] : '');

	$driverDetailsSql = "SELECT * FROM driver_details WHERE user_id = '$driverId'";  
	$driverDetailsResult = mysql_query($driverDetailsSql);
	$driverDetailsData = mysql_fetch_assoc($driverDetailsResult);
   
	if ($driverDetailsData) {
	    $id = @$driverDetailsData['id'];
	    $isDeleted = $this->updateDriverDetails(array('is_on_duty' => 'N','is_online' => 'N','vehicle_id'=>'0'), $id);
	    if ($isDeleted) {
		return SUCCESSFULLY_DONE;
	    }
	} else {
	    return NO_RECORD_FOUND;
	}
	return UNABLE_TO_PROCEED;
    }
    
    /**
     * This function used to get user completed booking
     * @params array $params
     * @return array 
     */
    public function getUserCompletedBooking($params = NULL) {

	$userId = (!empty($params['user_id']) ? $params['user_id'] : 0);
	
	$queryPayment = "SELECT driver.first_name as driver_first_name,driver.last_name as driver_last_name,bookings.price,IFNULL(bookings.total_miles, '0') total_miles,IFNULL(bookings.pickup_date, '') pickup_date,bookings.id as booking_id
			FROM bookings 
			LEFT JOIN users as driver ON bookings.driver_id = driver.id 
			WHERE bookings.booking_status = 5 AND bookings.user_id = $userId";
	
	$resPayment = mysql_query($queryPayment);
	
	if (mysql_num_rows($resPayment) > 0) {

	    $response = array();
	    while ($array = mysql_fetch_assoc($resPayment)) {
		
		$response[] = array(
		    'driver_first_name' => $array['driver_first_name'],
		    'driver_last_name' => $array['driver_last_name'],
		    'booking_id' => $array['booking_id'],
		    'pickup_date' => $array['pickup_date'],
		    'price' => $array['price'],
		    'miles' => $array['total_miles']
		);
	    }
	    return $response;
	} else {
	    return NO_RECORD_FOUND;
	}
    }
    
    /**
     * This function used to get user upcoming booking
     * @params array $params
     * @return array 
     */
    public function getUserUpcomingBooking($params = NULL) {

	$userId = (!empty($params['user_id']) ? $params['user_id'] : 0);
	
	$queryBooking = "SELECT  IFNULL(driver.first_name, '') first_name,IFNULL(driver.last_name, '') last_name,bookings.id,IFNULL(bookings.pickup_date, '') pickup_date,bookings.price,IFNULL(bookings.total_miles, '') total_miles,bookings.id as booking_id "
			. "FROM bookings "
			. "LEFT JOIN users as driver ON bookings.driver_id = driver.id "
			. "WHERE bookings.booking_status IN (1,2,4) AND bookings.user_id = $userId AND bookings.pickup_date > NOW() "
			. "ORDER BY bookings.pickup_date ASC"; 
	
	$resBooking = mysql_query($queryBooking);
	
	if (mysql_num_rows($resBooking) > 0) {

	    $response = array();
	    while ($array = mysql_fetch_assoc($resBooking)) {
		
		$response[] = $array;
		
//		$response[] = array(
//		    'booking_id' => $array['booking_id'],
//		    'price' => $array['price'],
//		    'miles' => $array['total_miles']
//		);
	    }
	    return $response;
	} else {
	    return NO_RECORD_FOUND;
	}
	
	return UNABLE_TO_PROCEED;
    }
    
    
    /**
     * This function used to get driver earning details
     * @params array $params
     * @return array 
     */
    public function getDriverEarnings($params = NULL) {
	
	$driverId = (!empty($params['driver_id']) ? $params['driver_id'] : 0);
	
	$queryTotal = "SELECT SUM(bookings.price) AS total
		    FROM transactions
		    LEFT JOIN bookings ON transactions.booking_id = bookings.id
		    WHERE bookings.driver_id = $driverId AND transactions.status = 2";
	$resTotal = mysql_query($queryTotal);
	$dataTotal = mysql_fetch_assoc($resTotal);
	
	$total = (!empty($dataTotal['total']) ? $dataTotal['total'] : 0);
	
	$queryPayment = "SELECT transactions.created AS transaction_date,transactions.driver_amount AS price,IFNULL(bookings.total_miles, '') total_miles,bookings.id as booking_id, booking_locations.source_address as source_address, booking_locations.destination_address as destination_address FROM transactions
			LEFT JOIN bookings ON transactions.booking_id = bookings.id LEFT JOIN booking_locations ON bookings.id = booking_locations.booking_id
			WHERE transactions.status IN ('1','2') AND bookings.driver_id = $driverId";
	// print_r($queryPayment); die;
	$resPayment = mysql_query($queryPayment);
	// print_r($resPayment); die;
	if (mysql_num_rows($resPayment) > 0) {

	    $response = array();
	    while ($array = mysql_fetch_assoc($resPayment)) {
		
		$response[] = array(
		    'booking_id' => $array['booking_id'],
		    'transaction_date' => $array['transaction_date'],
		    'pickup_address' => $array['source_address'],
		    'drop_address' => $array['destination_address'],
		    'price' => $array['price'],
		    'miles' => $array['total_miles']
		);
	    }
	    $result = array('total'=>$total,'data_list'=>$response);
	    return $result;
	} else {
	    return NO_RECORD_FOUND;
	}
    }
    
    
    
    
    /**
     * This function used to get driver payment history
     * @params array $params
     * @return array 
     */
    public function getDriverPaymentHistory($params = NULL) {

	$driverId = (!empty($params['driver_id']) ? $params['driver_id'] : 0);
	
	$queryPayment = "SELECT transactions.`status` as transaction_status ,WEEK(transactions.created) week,sum(transactions.driver_amount) as price,
			 CONCAT(DATE_FORMAT(DATE_ADD(transactions.created, INTERVAL(1-DAYOFWEEK(transactions.created)) DAY),'%Y-%m-%e'), ' TO ',    
			 DATE_FORMAT(DATE_ADD(transactions.created, INTERVAL(7-DAYOFWEEK(transactions.created)) DAY),'%Y-%m-%e')) AS DateRange
		         FROM `transactions` LEFT JOIN bookings ON transactions.booking_id = bookings.id 
		         WHERE bookings.driver_id = $driverId
		         GROUP BY YEARWEEK(transactions.created)";
	$resPayment = mysql_query($queryPayment);
	
	if (mysql_num_rows($resPayment) > 0) {

	    $response = array();
	    while ($array = mysql_fetch_assoc($resPayment)) {
		if($array['transaction_status']==2){
		    $transactionStatus = 'Paid';
		}else{
		    $transactionStatus = 'Pending';
		}
		
		$response[] = array(
		    'DateRange' => $array['DateRange'],
		    'price' => $array['price'],
		    'transaction_status' => $transactionStatus
		);
	    }
	    return $response;
	} else {
	    return NO_RECORD_FOUND;
	}
    }
    
    
    
    
    public function driverSetProfile($data = array()) {

	$ssn = $data['ssn'];
	$driving_license_no = $data['driving_license_no'];
	$country_id = $data['country_id'];
	$state_id = $data['state_id'];
	$vehicle = (isset($data['vehicle']) ? $data['vehicle'] : ''); 
	
	$vehicleTypeId = (!empty($data['vehicle_type_id']) ? $data['vehicle_type_id'] : '');
	$makeId = (!empty($data['make_id']) ? $data['make_id'] : '');
	$modelId = (!empty($data['model_id']) ? $data['model_id'] : '');
	$makeYear = (!empty($data['make_year']) ? $data['make_year'] : '');
	$color = (!empty($data['color']) ? $data['color'] : '');
	$plateNo = (!empty($data['plate_no']) ? $data['plate_no'] : '');
	$insurancePolicyDoc = (!empty($data['insurance_policy_doc']) ? $data['insurance_policy_doc'] : '');
	$registrationDoc = (!empty($data['registration_doc']) ? $data['registration_doc'] : '');
	$drivingLicenseDoc = (!empty($data['driving_license_doc']) ? $data['driving_license_doc'] : '');
	
	$question = (isset($data['question']) ? $data['question'] : ''); 
	$driverId = (isset($data['driver_id']) ? $data['driver_id'] : ''); 
	$dob = (isset($data['dob']) ? date('Y-m-d',strtotime($data['dob'])) : NULL); 

	$curr_time = time();
	
	$response = array();
	
	$vehicleData = array('vehicle_type_id'=>$vehicleTypeId,
		      'make_id'=>$makeId,
		      'model_id'=>$modelId,
		      'make_year'=>$makeYear,
		      'color'=>$color,
		      'plate_no'=>$plateNo,
		      'user_id'=>$driverId,
	      );

	if ($this->checkDriverDetailFieldUnique('ssn', $ssn, $driverId)) {
	    return 'SSN_ALREADY_EXISTED';
	} elseif ($this->checkDriverDetailFieldUnique('driving_license_no', $driving_license_no, $driverId)) {
	    return 'DRIVING_LICENSE_ALREADY_EXISTED';
	} else {
	    if (!empty($insurancePolicyDoc)) {
		$allowedExtension = array('doc','pdf','png','jpg','jpeg');
		$ex1 = explode(".", $insurancePolicyDoc['name']);
		$ext = end($ex1);
		$fileSize = $insurancePolicyDoc['size'];
		$maxFileSize = 2*(1024*1024);  
		if($fileSize > $maxFileSize){ //echo 'FileSize'.$fileSize.'MaxFileSize'.$maxFileSize;die;
		     return 'INSURANCE_POLICY_DOC_SIZE';
		}
		if(!in_array(strtolower($ext),$allowedExtension)){
		    return 'INSURANCE_POLICY_DOC_TYPE';
		}
		$insurance_image = $driverId . '-' . time() . "_insurance." . $ext;
		$vehicleData['insurance_policy_doc'] = $insurance_image;
		if (move_uploaded_file($insurancePolicyDoc['tmp_name'], VEHICLE_DOC_PATH . $insurance_image)) {
	    //	$this->resize($insurance_image, 300, 240, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_LARGE);
	    //	$this->resize($insurance_image, 150, 120, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_THUMB);
		} 
	    }
	    
	    if (!empty($registrationDoc)) {
		$allowedExtension = array('doc','pdf','png','jpg','jpeg');
		$ex1 = explode(".", $registrationDoc['name']);
		$ext = end($ex1);
		$fileSize = $registrationDoc['size'];
		$maxFileSize = 2*(1024*1024);
		if($fileSize > $maxFileSize){
		     return 'REGISTRATION_DOC_SIZE';
		}
		if(!in_array(strtolower($ext),$allowedExtension)){
		    return 'REGISTRATION_DOC_TYPE';
		}
		$registration_image = $driverId . '-' . time() . "_registration." . $ext;
		$vehicleData['registration_doc'] = $registration_image;
		if (move_uploaded_file($registrationDoc['tmp_name'], VEHICLE_DOC_PATH . $registration_image)) {
	    //	$this->resize($registration_image, 300, 240, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_LARGE);
	    //	$this->resize($registration_image, 150, 120, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_THUMB);
		}
	    }
	    
	    $driverData = array('ssn'=>$ssn,
			    'driving_license_no'=>$driving_license_no,
			    'user_id'=>$driverId,
		    );
	    
	    if (!empty($drivingLicenseDoc)) {
		$allowedExtension = array('doc','pdf','png','jpg','jpeg');
		$ex1 = explode(".", $drivingLicenseDoc['name']);
		$ext = end($ex1);
		$fileSize = $drivingLicenseDoc['size'];
		$maxFileSize = 2*(1024*1024);
		if($fileSize > $maxFileSize){
		     return 'LICENSE_DOC_SIZE';
		}
		if(!in_array(strtolower($ext),$allowedExtension)){
		    return 'LICENSE_DOC_TYPE';
		}
		$license_image = $driverId . '-' . time() . "_license." . $ext;
		$driverData['driving_license_doc'] = $license_image;
		if (move_uploaded_file($drivingLicenseDoc['tmp_name'], DRIVER_DOC_PATH . $license_image)) {
	    //	$this->resize($registration_image, 300, 240, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_LARGE);
	    //	$this->resize($registration_image, 150, 120, VEHICLE_DOC_PATH, VEHICLE_DOC_PATH_THUMB);
		}
	    }
	
	    $this->insertVehicles($vehicleData);
	    $this->saveDriverQuestions(array('question' => $question ,'driver_id' => $driverId));
	    
	    $querydd = "SELECT id FROM driver_details WHERE user_id = '{$driverId}' LIMIT 1"; 
	    $resultdd = mysql_query($querydd);
	    $num_rows_dd = mysql_num_rows($resultdd);
	    
	    if($num_rows_dd){	
		$dd = mysql_fetch_assoc($resultdd);
		$dd_id = $dd['id'];
		$driverDetailsId = $this->updateDriverDetails($driverData,$dd_id);
	    }else{
		$driverDetailsId = $this->insertDriverDetails($driverData);
	    }
	    
	    if ($driverDetailsId) {
		$userId = $driverId;
		$langCode = (!empty($params['lang_code']) ? $params['lang_code'] : DEFAULT_LANGUAGE);
		
		$saveUserData = array('dob'=>$dob,'country_id'=>$country_id,'state_id'=>$state_id); 
		$this->updateUsers($saveUserData,$userId);

		$query = "SELECT
			IFNULL(d.id, '') driver_id,
			IFNULL(d.first_name, '') first_name,
			IFNULL(d.last_name, '') last_name,
			IFNULL(d.email, '') email,
			IFNULL(d.phone, '') phone,
			IFNULL(d.photo, '') photo,
			IFNULL(d.password, '') password,
			IFNULL(d.customer_status, '') customer_status,
			IFNULL(d.driver_status, '') driver_status,
			IFNULL(d.login_from, '') login_from,
			IFNULL(d.last_login, '') last_login,
			IFNULL(d.user_type, '') user_type,
			IFNULL(d.address, '') address,
			IFNULL(d.zip_code, '') zip_code,
			IFNULL(d.phone, '') phone,
			IFNULL(cl.name, '') country_name,
			IFNULL(sl.name, '') state_name,
			IFNULL(cil.name, '') city_name,
			IFNULL(dd.ssn, '') ssn,
			IFNULL(dd.driving_license_no, '') driving_license_no
		    FROM
		      users AS d
		      LEFT JOIN driver_details AS dd
			ON dd.`user_id` = d.`id`
		      LEFT JOIN country_locales AS cl
			ON cl.`country_id` = d.`country_id`
			AND cl.`lang_code` = '$langCode'
		      LEFT JOIN state_locales AS sl
			ON sl.`state_id` = d.`state_id`
			AND sl.`lang_code` = '$langCode'
		      LEFT JOIN city_locales AS cil
			ON cil.`city_id` = d.`city_id`
			AND cil.`lang_code` = '$langCode'
		    WHERE d.id = $userId
		    AND d.user_type IN ('D','B');";   //  echo $query;die;

		$res = mysql_query($query);

		if (mysql_num_rows($res) > 0) {

		    $response = array();
		    while ($array = mysql_fetch_assoc($res)) {

			$driverId = $array['driver_id'];
			$image_thumb = $image_large = '';
			if ($array['photo'] != '') {
			    if (file_exists(USER_PHOTO_PATH_THUMB . $array['photo'])) {
				$image_thumb = USER_PHOTO_URL_THUMB . $array['photo'];
			    }
			    if (file_exists(USER_PHOTO_PATH_LARGE . $array['photo'])) {
				$image_large = USER_PHOTO_URL_LARGE . $array['photo'];
			    }
			}
			

			$vehicleDetailsJson = $this->getDriverVechicleDetail(array('driver_id' => $driverId, 'lang_code' => $langCode));
			
			$response[] = array(
			    'user_id'=> $array['driver_id'],
			    'first_name' => $array['first_name'],
			    'last_name' => $array['last_name'],
			    'email' => $array['email'],
			    'phone' => $array['phone'],
			    'password' => $array['password'],
			    'customer_status' => $array['customer_status'],
			    'driver_status' => $array['driver_status'],
			    'login_from' => $array['login_from'],
			    'last_login' => $array['last_login'],
			    'user_type' => $array['user_type'],
			    'image_thumb' => $image_thumb,
			    'image_large' => $image_large,
			    'address' => $array['address'],
			    'zip_code' => $array['zip_code'],
			    'phone' => $array['phone'],
			    'country_name' => $array['country_name'],
			    'state_name' => $array['state_name'],
			    'city_name' => $array['city_name'],
			    'ssn' => $array['ssn'],
			    'driving_license_no' => $array['driving_license_no'],
			    'vehicle_details' => $vehicleDetailsJson,
			);
		    }
		    return $response;
		} else { 
		    return 'UNABLE_TO_PROCEED';
		}
	    } else { 
		return 'UNABLE_TO_PROCEED';
	    }
	}
	return 'UNABLE_TO_PROCEED';
    }
    
    /**
     * This function used to update usertype in user table
     * @params int $user_id user id
     * @return array 
     */
    public function switchMode($params = NULL) {

	$userId = (!empty($params['user_id']) ? $params['user_id'] : '');
	
	$userSql = "SELECT * FROM users WHERE id = '$userId'"; 
	$userResult = mysql_query($userSql);
	$userData = mysql_fetch_assoc($userResult);
	if ($userData) {
	    $userType = 'B';
/*	    if($userData['user_type'] == 'D'){
		$userType = 'N';
	    }elseif($userData['user_type'] == 'N'){
		$userType = 'D';
	    } */
	    $isUpdated = $this->updateUsers(array('user_type' => $userType), $userId);
	    if ($isUpdated) {
		
		$langCode = (!empty($params['lang_code']) ? $params['lang_code'] : DEFAULT_LANGUAGE);

		$query = "SELECT
			IFNULL(d.id, '') driver_id,
			IFNULL(d.first_name, '') first_name,
			IFNULL(d.last_name, '') last_name,
			IFNULL(d.photo, '') photo,
			IFNULL(d.user_type, '') user_type,
			IFNULL(d.address, '') address,
			IFNULL(d.zip_code, '') zip_code,
			IFNULL(d.phone, '') phone,
			IFNULL(cl.name, '') country_name,
			IFNULL(sl.name, '') state_name,
			IFNULL(cil.name, '') city_name,
			IFNULL(dd.ssn, '') ssn,
			IFNULL(dd.driving_license_no, '') driving_license_no
		    FROM
		      users AS d
		      LEFT JOIN driver_details AS dd
			ON dd.`user_id` = d.`id`
		      LEFT JOIN country_locales AS cl
			ON cl.`country_id` = d.`country_id`
			AND cl.`lang_code` = '$langCode'
		      LEFT JOIN state_locales AS sl
			ON sl.`state_id` = d.`state_id`
			AND sl.`lang_code` = '$langCode'
		      LEFT JOIN city_locales AS cil
			ON cil.`city_id` = d.`city_id`
			AND cil.`lang_code` = '$langCode'
		    WHERE d.id = $userId
		    AND d.user_type IN ('D','B') ORDER BY dd.id DESC LIMIT 1 ;"; // echo $query;die;

		$res = mysql_query($query);
		$response = array();

		if (mysql_num_rows($res) > 0) {

		  
		    while ($array = mysql_fetch_assoc($res)) {

			$driverId = $array['driver_id'];
			$image_thumb = $image_large = '';
			if ($array['photo'] != '') {
			    if (file_exists(USER_PHOTO_PATH_THUMB . $array['photo'])) {
				$image_thumb = USER_PHOTO_URL_THUMB . $array['photo'];
			    }
			    if (file_exists(USER_PHOTO_PATH_LARGE . $array['photo'])) {
				$image_large = USER_PHOTO_URL_LARGE . $array['photo'];
			    }
			}

			$vehicleDetailsJson = $this->getDriverVechicleDetail(array('driver_id' => $driverId, 'lang_code' => $langCode));

			$response[] = array(
			/*    'first_name' => $array['first_name'],
			    'last_name' => $array['last_name'],
			    'user_type' => $array['user_type'],
			    'image_thumb' => $image_thumb,
			    'image_large' => $image_large,
			    'address' => $array['address'],
			    'zip_code' => $array['zip_code'],
			    'phone' => $array['phone'],
			    'country_name' => $array['country_name'],
			    'state_name' => $array['state_name'],
			    'city_name' => $array['city_name'], 
			    'ssn' => $array['ssn'], */
			    'driving_license_no' => $array['driving_license_no'],
			    'user_id' => $array['driver_id'],
			//    'vehicle_details' => $vehicleDetailsJson,
			);
		    }
		}
		return $response;
	    }
	} else {
	    return NO_RECORD_FOUND;
	}
	return UNABLE_TO_PROCEED;
    }
    
     /**
     * This function used to change status of driver in booking location table
     * @params int $driver_id driver id
     * @params string $arrived arrived
     * @params int $bookingLocationId booking_location_id
     * @return array 
     */
    public function driverArrived($params = NULL) {
	$driverId = (!empty($params['driver_id']) ? $params['driver_id'] : '');
	$arrived = (isset($params['arrived']) ? $params['arrived'] : '');
	$bookingLocationId = (isset($params['booking_location_id']) ? $params['booking_location_id'] : '');
	
	if(!in_array($arrived, array('S','D'))){
	    return INVALID_ARRIVE_TYPE;
	}

	$bookingLocationSql = "SELECT bl.id,bl.booking_id FROM booking_locations as bl LEFT JOIN bookings as b on bl.booking_id = b.id WHERE b.driver_id = $driverId and bl.id = $bookingLocationId";
	$bookingLocationResult = mysql_query($bookingLocationSql);
	$bookingLocationData = mysql_fetch_assoc($bookingLocationResult); 
	//echo $bookingLocationSql;die;
	if ($bookingLocationData) {
	    if($arrived=='S'){
		$isUpdated = $this->updateBookingLocations(array('driver_arrived_source' => 'Y'), $bookingLocationId);
		$this->updateLocation(array('id'=>$bookingLocationId,'type'=>$arrived));
		$this->sendLocationPush(array('id'=>$bookingLocationId,'type'=>'P'));
	    }elseif($arrived=='D'){
		$isUpdated = $this->updateBookingLocations(array('driver_arrived_destination' => 'Y'), $bookingLocationId);
		$this->updateLocation(array('id'=>$bookingLocationId,'type'=>$arrived));
		//$this->sendLocationPush(array('id'=>$bookingLocationId,'type'=>'P'));
		$this->sendLocationPush(array('id'=>$bookingLocationId,'type'=>$arrived));
	    }
	  //  var_dump($isUpdated);die;
	    if ($isUpdated) { 
		$bookingId = $bookingLocationData['booking_id'];
		$updateData = array('booking_status'=>'4'); 
		$this->updateBookings($updateData,$bookingId);
		return SUCCESSFULLY_DONE;
	    }
	} else {
	    return NO_RECORD_FOUND;
	}
	return UNABLE_TO_PROCEED;
    }
        
     /**
     * This function used to send the notification to rider when user starts navigation
     * @params int $driver_id driver id
     * @params string $arrived arrived
     * @params int $bookingLocationId booking_location_id
     * @return array 
     */
    public function driverNavigate($params = NULL) {
		$driverId = (!empty($params['driver_id']) ? $params['driver_id'] : '');
		$arrived = (isset($params['arrived']) ? $params['arrived'] : '');
		$bookingLocationId = (isset($params['booking_location_id']) ? $params['booking_location_id'] : '');
		
		if(!in_array($arrived, array('S','D'))){
		    return INVALID_ARRIVE_TYPE;
		}

		$bookingLocationSql = "SELECT bl.id,bl.booking_id FROM booking_locations as bl LEFT JOIN bookings as b on bl.booking_id = b.id WHERE b.driver_id = $driverId and bl.id = $bookingLocationId";
		$bookingLocationResult = mysql_query($bookingLocationSql);
		$bookingLocationData = mysql_fetch_assoc($bookingLocationResult); 
		//echo $bookingLocationSql;die;
		if ($bookingLocationData) {
			$this->sendLocationPush(array('id'=>$bookingLocationId,'type'=>$arrived), 'driver');
			/* to send notification to rider priyanka*/
			$this->sendLocationPush(array('id'=>$bookingLocationId,'type'=>$arrived));
			return SUCCESSFULLY_DONE;
		} else {
		    return NO_RECORD_FOUND;
		}
		return UNABLE_TO_PROCEED;
    }
    
    /**
     * This function used to book a driver 
     * @params int $user_id user id
     * @params json $booking_address json array of multiple locations [{"source_company_name":"B R Softech1","source_address":"Triven nagar","source_lat":"26.912326","source_long":"75.787253","destination_company_name":"A3 logics","destination_address":"Sitapura","destination_lat":"26.912912","destination_long":"75.797188"}]
     * @return array 
     */
    function calculateAddress($params = NULL){
	
	$bookingAddressJson = $params['booking_address'];
	$totalMiles = 0;
	$bookingAddress = json_decode($bookingAddressJson, true);
	foreach ($bookingAddress as $bAddress) {
	    $sourcelat = $bAddress['source_lat'];
	    $sourcelon = $bAddress['source_long'];
	    $desLat = $bAddress['destination_lat'];
	    $desLon = $bAddress['destination_long'];
	    $totalMiles = $totalMiles + $this->distance($sourcelat, $sourcelon, $desLat, $desLon, 'K');
	}
	$totalMiles = round($totalMiles, 2);
	$result = array('miles'=>$totalMiles);
	
	return $result;
    }
    
     public function addcreditcard($user_id, $cardholdername, $cvv, $expirationDate, $number, $is_default) {
	// gte customer_id from users table
	$userSql = "select credit_card_id,customer_id, email, concat_ws(' ', first_name, last_name) as name from users where id='$user_id'"; // echo $userSql;die;
	$chkusr = mysql_query($userSql);
	$usrdat = mysql_fetch_assoc($chkusr);
	$customer_id = $usrdat['customer_id'];
	
	if (!$usrdat['customer_id']) {
	    // generate customer and create customer id
	    $name = explode(",", $usrdat['name']);
	    $customer_array = array(
		'firstName' => @$name[0],
		'lastName' => @$name[1],
		'email' => $usrdat['email'],
	    );
	    $server_output = $this->createcustomer($customer_array);
	    $response = $server_output;
	    $customer_id = $response['customer_id'];
//	    if ($response['success']) {
//		// update customer id to passangers table
//		$update_user = "UPDATE users set customer_id='" . $response['customer_id'] . "' where id='$user_id'";
//		$res = mysql_query($this->conn, $update_user);
//	    }
	}

	// store credit card in braintree account
/*	require_once 'Setup.php';
	Configuration::environment(BRAINTREE_ENVIRONMENT);
	Configuration::merchantId(BRAINTREE_MERCHANTID);
	Configuration::publicKey(BRAINTREE_PUBLICKEY);
	Configuration::privateKey(BRAINTREE_PRIVATEKEY);  */

    require_once APIPATH . 'v1/braintree-php/lib/Braintree.php';

	Braintree_Configuration::environment(BRAINTREE_ENVIRONMENT);
	Braintree_Configuration::merchantId(BRAINTREE_MERCHANTID);
	Braintree_Configuration::publicKey(BRAINTREE_PUBLICKEY);
	Braintree_Configuration::privateKey(BRAINTREE_PRIVATEKEY);
	    
	$arr = array( 'cardholderName' => $cardholdername,
	  'customerId' => $customer_id,
	  'cvv' => $cvv,
	  'expirationDate' => $expirationDate,
	  'number' => $number,
	  'options' => array(
	      'makeDefault' => ($is_default == 'Y' ? 1 : 0)
	            )); 
	
	$result = Braintree_CreditCard::create($arr);
	
	$this->paymentlogs(0,$arr,$result);
	// print_r($result);die;
	if ($result->success) {

	    $uniquenumberidentifier = $result->creditCard->uniqueNumberIdentifier;
	    $token = $result->creditCard->token;

	    // check credit card already exists or not		
	    $chkcard = mysql_query("select id from credit_cards where uniquenumberidentifier='$uniquenumberidentifier'");
	    
	    if (mysql_num_rows($chkcard)) {

		// delete card from braintree account
	//	Braintree_CreditCard::delete($token);

		// return error message here
		return (object) array('success' => false, 'message' => 'Sorry credit card already exists');
	    }

	    if ($is_default == 'Y') {
		// set all creditcards to set default false	
		$update_user = "UPDATE credit_cards set is_default='N' where user_id='$user_id'"; // echo $update_user;die;
		$res = mysql_query($update_user);
	    }
	    $maskedNumber = $result->creditCard->maskedNumber;
	    
	    $creditCardData = array('user_id'=>$user_id,
		'cardholdername'=>$cardholdername,
		'customerid'=>$customer_id,
		'expirationdate'=>$expirationDate,
		'number'=>$maskedNumber,
		'token'=>$token,
		'uniquenumberidentifier'=>$uniquenumberidentifier,
		'token'=>$token,
		'is_default'=>$is_default,
		'created'=>date('Y-m-d H:i:s'),
	    );
	    
	    $creditCardId = $this->insertCreditCards($creditCardData);
	    
	    if ($is_default == 'Y' || $usrdat['credit_card_id'] =='') {
		$userSaveData = array('credit_card_id'=>$creditCardId,'customer_id'=>$customer_id);
		$this->updateUsers($userSaveData,$user_id);
	    }

	    // store all credit card details to database				
//	    $save_card = "INSERT INTO credit_cards(user_id, cardholdername, customerid, expirationdate, `number`, token, uniquenumberidentifier, is_default, created) 
//    							VALUES ('$user_id', '$cardholdername','$customer_id','$expirationDate','$maskedNumber','$token','$uniquenumberidentifier','$is_default','" . date('Y-m-d H:i:s') . "')";
//	    mysql_query($save_card);
	}
	return $result;
    }
    
    public function createcustomer($customer_array) {

/*	require_once 'Setup.php';
	Configuration::environment(BRAINTREE_ENVIRONMENT);
	Configuration::merchantId(BRAINTREE_MERCHANTID);
	Configuration::publicKey(BRAINTREE_PUBLICKEY);
	Configuration::privateKey(BRAINTREE_PRIVATEKEY); */

	require_once APIPATH . 'v1/braintree-php/lib/Braintree.php';

	Braintree_Configuration::environment(BRAINTREE_ENVIRONMENT);
	Braintree_Configuration::merchantId(BRAINTREE_MERCHANTID);
	Braintree_Configuration::publicKey(BRAINTREE_PUBLICKEY);
	Braintree_Configuration::privateKey(BRAINTREE_PRIVATEKEY);

	$result = Braintree_Customer::create($customer_array);
	$this->paymentlogs(0,$customer_array,$result);
	$response = array('success' => $result->success, 'customer_id' => @$result->customer->id);
	return $response;
    }
    
    public function bookingComplete($param=NULL){
	
		date_default_timezone_set('UTC');
		$updtd = date('Y-m-d H:i:s');
		
		$bookingId		= $param['booking_id'];
		$travel_route 	= $param['travel_route'];
		
		$bookingSql = "SELECT booking_locations.driver_arrived_source,bookings.id
				FROM booking_locations
				LEFT JOIN bookings ON booking_locations.booking_id = bookings.id
				WHERE bookings.id = $bookingId AND bookings.booking_status IN ('2','4') AND (booking_locations.driver_arrived_source ='N' OR booking_locations.driver_arrived_destination ='N');";
		
		$chkBooking = mysql_query($bookingSql);
//		 print_r($chkBooking); die;
		if (mysql_num_rows($chkBooking) > 0) {
		     return BOOKING_COMPLETED_ARRIVED_ERROR;
		}
	
	// print_r($param); die;
//	$flag = 0;
//	if (mysql_num_rows($chkBooking) > 0) {
//	    while ($array = mysql_fetch_assoc($chkBooking)) {
//		if($flag ==0 && $array['driver_arrived_source']=='N'){
//		    $flag = 1;
//		}
//	    }
//	    if($flag==0){
//		 return BOOKING_COMPLETED_ARRIVED_ERROR;
//	    }
//	}
	
		$bookingSql = "SELECT bookings.price,credit_cards.token,transactions.id,bookings.driver_id
				FROM bookings
				LEFT JOIN users ON bookings.user_id = users.id
				LEFT JOIN credit_cards ON users.credit_card_id = credit_cards.id
				LEFT JOIN transactions ON transactions.booking_id = bookings.id
				WHERE bookings.id = $bookingId AND bookings.booking_status IN ('4','2')"; 
		
		$chkBooking = mysql_query($bookingSql);
		$bookingData = mysql_fetch_assoc($chkBooking);
		$payment_method_token = $bookingData['token'];
		$amount = $bookingData['price'];
		$id = $bookingData['id'];
		$driverId = $bookingData['driver_id'];
	//	 echo $amount; echo '-----------'.$payment_method_token; echo '-----------'.$id; die;
		if (!empty($amount) && !empty($payment_method_token)) {
		    // echo 'dsfs'; die;
		    $queryb = "SELECT * 
			    FROM `bank_details` as bd
			    WHERE bd.user_id = $driverId LIMIT 1";
		    $resb = mysql_query($queryb);

		    if (mysql_num_rows($resb) > 0) {
				$bankArr = mysql_fetch_assoc($resb); 
				$subMerchantId = $bankArr['sub_merchant_id'];
		    } else {
				return INVALID_DRIVER_BANK_ACCOUNT;
		    }   

		    require_once APIPATH . 'v1/braintree-php/lib/Braintree.php';

			Braintree_Configuration::environment(BRAINTREE_ENVIRONMENT);
			Braintree_Configuration::merchantId(BRAINTREE_MERCHANTID);
			Braintree_Configuration::publicKey(BRAINTREE_PUBLICKEY);
			Braintree_Configuration::privateKey(BRAINTREE_PRIVATEKEY);

	/*	    require_once 'Setup.php';
		    Configuration::environment(BRAINTREE_ENVIRONMENT);
		    Configuration::merchantId(BRAINTREE_MERCHANTID);
		    Configuration::publicKey(BRAINTREE_PUBLICKEY);
		    Configuration::privateKey(BRAINTREE_PRIVATEKEY); */
		    
		    $globalSettings = $this->getGlobalSetting();

		    if (!empty($globalSettings)) {
				$pidaCommission = $globalSettings['admin_commission'];
				$pidaFee = $globalSettings['admin_pida_fee'];
		    } else {
				$pidaCommission = 0;
				$pidaFee = 0;
		    }
		    
		    $adminAmt = $amount * $pidaCommission/100;
		    $totalAdminAmt = $adminAmt + $pidaFee;
//		    $driveAmt = $amount - $totalAdminAmt;
		    $driveAmt = $amount;
		    
		    $adminAmtPaypl = round($totalAdminAmt,2);
		    $driveAmt = round($driveAmt,2);
		    
		    $arr = ['amount' => $driveAmt,
			    'merchantAccountId' => $subMerchantId,
			//  'paymentMethodNonce' => nonceFromTheClient,
			    'options' => [
				    'submitForSettlement' => True,
				    'holdInEscrow' => True
			    ],
			    'serviceFeeAmount' => $adminAmtPaypl
			    ];

		/*    $arr = [
			'amount' => $amount,
			'options' => [
			    'submitForSettlement' => True
			]
		    ]; */
		    
		    if ($payment_method_token) {
				$arr['paymentMethodToken'] = $payment_method_token;
		    } else {
				$arr['paymentMethodNonce'] = $payment_method_nonce;
		    }
		    $result = Braintree_Transaction::sale($arr);
		    
		    $this->paymentlogs($bookingId,$arr,$result);
		  
		    if ($result->success) {

				$transaction = $result->transaction;
				$transaction_id = $transaction->id;

				$transactionData = array('booking_id' => $bookingId, 'transaction_id' => $transaction_id, 'created' => $updtd,'status'=>'1','driver_amount'=>$driveAmt,'admin_amount'=>$adminAmt,'admin_percent'=>$pidaCommission,'admin_pida_fee'=>$pidaFee,'admin_total_amount'=>$adminAmtPaypl);
				$transaction_id = $this->insertTransactions($transactionData);
				
				$updateData = array('booking_status'=>'5','status'=>'3','transaction_id'=>$transaction_id,'travel_route'=>$travel_route);
				$this->updateBookings($updateData,$bookingId);
				
				$query = "SELECT b.*,u.device_token,u.device_type"
					. " FROM"
					. " bookings AS b"
					. " LEFT JOIN users AS u on b.user_id = u.id"
					. " WHERE b.id = $bookingId AND u.device_token != '';"; // echo $query;die;

				$res = mysql_query($query);

				if (mysql_num_rows($res) > 0) {
				    $response = array();
				    while ($array = mysql_fetch_assoc($res)) {
					$response[] = array(
					    'booking_id' => $array['id'],
					    'price' => $array['price'],
					    'device_token' => $array['device_token'],
					    'device_type' => $array['device_type'],
					);
					$this->pushNotification($response, BOOKING_PAYMENT_NOTIFY_USER);
				    }
				}
				
				$query = "SELECT b.*,d.device_token,d.device_type"
					. " FROM"
					. " bookings AS b"
					. " LEFT JOIN users AS d on b.driver_id = d.id"
					. " WHERE b.id = $bookingId AND d.device_token != '';"; //  echo $query;die;

				$res = mysql_query($query);

				if (mysql_num_rows($res) > 0) {
				    $response = array();
				    while ($array = mysql_fetch_assoc($res)) {
					$response[] = array(
					    'booking_id' => $array['id'],
					    'price' => $driveAmt,
					    'device_token' => $array['device_token'],
					    'device_type' => $array['device_type'],
					);
					$this->pushNotification($response, BOOKING_PAYMENT_NOTIFY_DRIVER);
				    }
				}
				
				$this->sendBookingEmail($bookingId);
				
				return SUCCESSFULLY_DONE;
		    } else {
				$paymentStatus = 2;
				$rejectReason = $result->message;
				
				$transactionData = array('booking_id' => $bookingId, 'created' => $updtd,'status'=>'1','driver_amount'=>$driveAmt,'admin_amount'=>$adminAmt,'admin_percent'=>$pidaCommission,'admin_pida_fee'=>$pidaFee,'admin_total_amount'=>$adminAmtPaypl,'payment_status'=>$paymentStatus,'reject_reason'=>$rejectReason);
				
				$transaction_id = $this->insertTransactions($transactionData);
				
				$updateData = array('booking_status'=>'6','transaction_id'=>$transaction_id,'travel_route'=>$travel_route);
				$result = $this->updateBookings($updateData,$bookingId);
				return SUCCESSFULLY_DONE;
				// print_r($result); die;
			
		    }
		} else {
		    return NO_RECORD_FOUND;
		}
		
		return UNABLE_TO_PROCEED;
    }
    
    
     /**
     * This function used to rate driver for booking
     * @params int $user_id user id
     * @params string $arrived arrived
     * @params int $bookingLocationId booking_location_id
     * @return array 
     */
    public function bookingRating($params = NULL) {
	$userId = (!empty($params['user_id']) ? $params['user_id'] : '');
	$bookingId = (!empty($params['booking_id']) ? $params['booking_id'] : '');
	$rating = (!empty($params['rating']) ? $params['rating'] : '');
	$comment = (!empty($params['comment']) ? $params['comment'] : '');
	
	$bookingSql = "SELECT b.id FROM bookings as b where b.id = $bookingId AND b.user_id = $userId";
	$bookingResult = mysql_query($bookingSql);
	
	if (mysql_num_rows($bookingResult)) {
	    
	    $ratingSql = "SELECT r.id FROM ratings as r where r.booking_id = $bookingId";
	    $ratingResult = mysql_query($ratingSql);
	    
	    if(mysql_num_rows($ratingResult)){
		return USER_ALREADY_RATE;
	    }
	    
	    $saveRatings = array('booking_id'=>$bookingId,'rating'=>$rating,'comment'=>$comment,'created'=>$updtd = date('Y-m-d H:i:s'));
	    $ratingId = $this->insertRatings($saveRatings);
	    if ($ratingId) {
		return SUCCESSFULLY_DONE;
	    }
	}else{
	    return NO_RECORD_FOUND;
	}
	
	return UNABLE_TO_PROCEED;
    }
    
    /**
     * This function used to get credit cards
     * @params array $params
     * @return array 
     */
    public function getCreditCard($params = NULL) {

	$userId = (!empty($params['user_id']) ? $params['user_id'] : 0);
	
	$querycc = "SELECT * 
		         FROM `credit_cards` as cc
		         WHERE cc.user_id = $userId ORDER BY is_default ASC";
	$rescc = mysql_query($querycc);
	
	if (mysql_num_rows($rescc) > 0) {
	    $response = array();
	    while ($array = mysql_fetch_assoc($rescc)) {
		$response[] = $array;
	    }
	    return $response;
	} else {
	    return NO_RECORD_FOUND;
	}
    }
    
      /**
     * This function used to accept booking request
     * @params int $driver_id driver id
     * @params int $booking_id booking id
     * @return array 
     */
    public function setCreditCard($data = array()) {
	$creditCardId = $data['credit_card_id'];
	$userId = $data['user_id'];

	$querycc = "SELECT * 
		         FROM `credit_cards` as cc
		         WHERE cc.user_id = $userId AND cc.id = $creditCardId";
	$rescc = mysql_query($querycc);
	$cc = mysql_fetch_assoc($rescc);

	if (!empty($cc)) {
	    // set all creditcards to set default false	
	    $update_cc = "UPDATE credit_cards set is_default='N' where user_id='$userId'";
	    $res = mysql_query($update_cc);
	    
	    $update_cc = "UPDATE credit_cards set is_default='Y' where id='$creditCardId'";
	    $res = mysql_query($update_cc);
	    
	    if($res){
		$userSaveData = array('credit_card_id'=>$creditCardId);
		$updateUser = $this->updateUsers($userSaveData,$userId);
		if($updateUser){
		    return SUCCESSFULLY_DONE;
		}
	    }
	} else {
	    return NO_RECORD_FOUND;
	}
	
	return UNABLE_TO_PROCEED;
    }
    
    public function bookingCancel($param=NULL){
	
	$bookingId = $param['booking_id'];
	
	$bookingSql = "SELECT booking_locations.driver_arrived_source,bookings.id
			FROM booking_locations
			LEFT JOIN bookings ON booking_locations.booking_id = bookings.id
			WHERE bookings.id = $bookingId AND bookings.status IN ('0','1')"; //  echo $bookingSql;die;
	
	$chkBooking = mysql_query($bookingSql);
	
	$flag = 0;
	if (mysql_num_rows($chkBooking) > 0) {
	    while ($array = mysql_fetch_assoc($chkBooking)) {
		if($flag ==0 && $array['driver_arrived_source']=='Y'){
		    $flag = 1;
		}
	    }
	}
	
	if($flag==0){
	    
	    $bookingSql = "SELECT * from bookings where bookings.id = $bookingId AND bookings.pickup_date > NOW() + INTERVAL -15 MINUTE";
	    $chkBooking = mysql_query($bookingSql);
	    if (mysql_num_rows($chkBooking) == 0) {
		return BOOKING_CANCEL_TIME_ERROR;
	    }
	    
	    $updateData = array('booking_status'=>'3','status'=>'2');
	    $isUpdated = $this->updateBookings($updateData,$bookingId);

	    if (!empty($isUpdated)) { 
		
		$query = "SELECT b.*,d.device_token,d.device_type"
			. " FROM"
			. " bookings AS b"
			. " LEFT JOIN users AS d on b.driver_id = d.id"
			. " WHERE b.id = $bookingId AND d.device_token != '';";  // echo $query;die;
			
		$res = mysql_query( $query);
		
		if (mysql_num_rows($res) > 0) {
			
		    $response = array();
		    while ($array = mysql_fetch_assoc($res)) {
			$response[] = array(
			    'booking_id' => $array['id'],
			    'price' => $array['price'],
			    'device_token' => $array['device_token'],
			    'device_type' => $array['device_type'],
			);
			$this->pushNotification($response, BOOKING_CANCEL_NOTIFY_DRIVER);
		    }
		}
		return SUCCESSFULLY_DONE;
	    }
	}else{
	    return BOOKING_CANCEL_ERROR;
	}
	
	return UNABLE_TO_PROCEED;
    }
    
    public function riderBookingCancel($param=NULL){
	
	$bookingId = $param['booking_id'];
	
	$bookingSql = "SELECT booking_locations.driver_arrived_source,bookings.id
			FROM booking_locations
			LEFT JOIN bookings ON booking_locations.booking_id = bookings.id
			WHERE bookings.id = $bookingId AND bookings.status IN ('0','1')"; //  echo $bookingSql;die;
	
	$chkBooking = mysql_query($bookingSql);
	
	$flag = 0;
	if (mysql_num_rows($chkBooking) > 0) {
	    while ($array = mysql_fetch_assoc($chkBooking)) {
		if($flag ==0 && $array['driver_arrived_source']=='Y'){
		    $flag = 1;
		}
	    }
	}
	
	if($flag==0){
	    
	    $bookingSql = "SELECT * from bookings where bookings.id = $bookingId AND (bookings.pickup_date + INTERVAL -15 MINUTE) > NOW();";
	    
	    $chkBooking = mysql_query($bookingSql);
	    
	//    echo mysql_num_rows($chkBooking);die;
	    
	    if (mysql_num_rows($chkBooking) == 0) {
		return BOOKING_CANCEL_RIDER_TIME_ERROR;
	    }
	    
	    $updateData = array('booking_status'=>'3','status'=>'2');
	    $isUpdated = $this->updateBookings($updateData,$bookingId);

	    if (!empty($isUpdated)) { 
		
		$query = "SELECT b.*,d.device_token,d.device_type"
			. " FROM"
			. " bookings AS b"
			. " LEFT JOIN users AS d on b.driver_id = d.id"
			. " WHERE b.id = $bookingId AND d.device_token != '';";  // echo $query;die;
			
		$res = mysql_query( $query);
		
		if (mysql_num_rows($res) > 0) {
			
		    $response = array();
		    while ($array = mysql_fetch_assoc($res)) {
			$response[] = array(
			    'booking_id' => $array['id'],
			    'price' => $array['price'],
			    'device_token' => $array['device_token'],
			    'device_type' => $array['device_type'],
			);
			$this->pushNotification($response, BOOKING_CANCEL_NOTIFY_DRIVER);
		    }
		}
		return SUCCESSFULLY_DONE;
	    }
	}else{
	    return BOOKING_CANCEL_ERROR;
	}
	
	return UNABLE_TO_PROCEED;
    }
    
    public function logout($param=NULL){
	
	$id = $param['user_id'];
	
	if(!empty($id)){
	    $time = time();
	    $update_user = "UPDATE users set `lat`=NULL, `long`=NULL, `device_type`=NULL, `device_token`=NULL, ";
	    $update_user.="modified=$time where id=$id"; //echo $update_user;die;
	    $user_res = mysql_query($update_user);
	  
	    $driverDetailsSql = "SELECT * FROM driver_details WHERE user_id = '$id'";  
	    $driverDetailsResult = mysql_query($driverDetailsSql);
	    $driverDetailsData = mysql_fetch_assoc($driverDetailsResult);
	    if ($driverDetailsData) {
		$id = @$driverDetailsData['id'];
		$isDeleted = $this->updateDriverDetails(array('is_on_duty' => 'N','is_online' => 'N','vehicle_id'=>'0'), $id);
	    }
	    
	    if($user_res){
		return SUCCESSFULLY_DONE;
	    }
	}
	
	return UNABLE_TO_PROCEED;
    }

    public function check_device_token($param=NULL){
	
	$id = $param['user_id'];
	$deviceToken = $param['device_token'];

	$userSql = "SELECT id FROM users WHERE id = $id AND device_token IS NOT NULL AND device_token = '$deviceToken';";  
    $userResult = mysql_query($userSql);
    $userData = mysql_fetch_assoc($userResult);

    $res = array('is_device_token_matched'=>'N');

    if ($userData) {
    	$res['is_device_token_matched'] = 'Y';
    }
	
	return $res;
    }
    
    /**
     * This function used to get booking details
     * @params array $params
     * @return array 
     */
    public function getBookingDetails($params = NULL) {

	$userId = (!empty($params['user_id']) ? $params['user_id'] : 0);
	$bookingId = (!empty($params['booking_id']) ? $params['booking_id'] : 0);
	$langCode = (!empty($params['lang_code']) ? $params['lang_code'] : DEFAULT_LANGUAGE);
	
	/*$queryb = "SELECT * 
		    FROM `bookings` as b
		    WHERE b.id=$bookingId "; */

    $queryb = "SELECT b.id as booking_id,b.booking_status,b.price,b.pickup_lat,b.pickup_long,b.total_miles,b.price,b.dimension,b.cubicfeet,u.first_name,u.last_name,u.photo,u.phone,d.device_type,d.device_type,d.device_token,IFNULL(b.cargo_type_notes,'') cargo_type_notes,IFNULL(b.delivery_type_notes,'') delivery_type_notes,IFNULL(b.lbh_dimension,'') lbh_dimension,IFNULL(d.lang_code, '" . $langCode . "') lang_code
    FROM
    bookings AS b
    LEFT JOIN users AS u
    ON b.user_id = u.id
    LEFT JOIN users AS d
    ON b.driver_id = d.id
    WHERE b.id = $bookingId;"; 

	$resb = mysql_query($queryb);
	
	if (mysql_num_rows($resb) > 0) {
	    $response = array();
	    while ($array = mysql_fetch_assoc($resb)) {

	    	$image_thumb = $image_large = '';
		    if ($array['photo'] != '') {
			if (file_exists(USER_PHOTO_PATH_THUMB . $array['photo'])) {
			    $image_thumb = USER_PHOTO_URL_THUMB . $array['photo'];
			}
			if (file_exists(USER_PHOTO_PATH_LARGE . $array['photo'])) {
			    $image_large = USER_PHOTO_URL_LARGE . $array['photo'];
			}
		    }
		    $bookingAddress = $this->getBookingLocationsArr(array('booking_id' => $array['booking_id']));
		    
		    $bookingCargoTypes = $this->getBookingCargoTypesArr(array('booking_id' => $array['booking_id'],'lang_code'=>$array['lang_code']));
		    $bookingDeliveryTypes = $this->getBookingDeliveryTypesArr(array('booking_id' => $array['booking_id'],'lang_code'=>$array['lang_code']));

		    $response[] = array(
			'booking_id' => $array['booking_id'],
			'booking_status' => $array['booking_status'],
			'price' => $array['price'],
			'first_name' => $array['first_name'],
			'last_name' => $array['last_name'],
			'pickup_lat' => $array['pickup_lat'],
			'pickup_long' => $array['pickup_long'],
			'image_thumb' => $image_thumb,
			'image_large' => $image_large,
			'booking_cargo_types' => $bookingCargoTypes,
			'booking_delivery_types' => $bookingDeliveryTypes,
			'cargo_type_notes' => $array['cargo_type_notes'],
			'delivery_type_notes' => $array['delivery_type_notes'],
			'booking_address' => $bookingAddress,
			'total_miles' => $array['total_miles'],
			'price' => $array['price'],
			'dimension' => $array['dimension'],
			'cubicfeet' => $array['cubicfeet'],
			'phone' => $array['phone'],
			'device_token' => $array['device_token'],
			'device_type' => $array['device_type'],
			'lbh_dimension' => $array['lbh_dimension'],
		    );

	//	$response[] = $array;
	    }
	    return $response;
	} else {
	    return NO_RECORD_FOUND;
	}
	return UNABLE_TO_PROCEED;
    }
    
    public function setBankDetails($params = NULL) {
	
	$langCode = DEFAULT_LANGUAGE;
	
	$userId = (!empty($params['user_id']) ? $params['user_id'] : 0);
	$accountType = (!empty($params['account_type']) ? $params['account_type'] : 0);
	$routerNo = (!empty($params['router_no']) ? $params['router_no'] : 0);
	$accountNo = (!empty($params['account_no']) ? $params['account_no'] : 0);
	
	if(!in_array($accountType, array('S','C'))){
		return INVALID_ACCOUNT_TYPE;
	}

	$select_bank = "SELECT * FROM bank_details WHERE user_id = '{$userId}' LIMIT 1";
	$bank_res = mysql_query($select_bank);
	$bank = mysql_fetch_assoc($bank_res);
	$currentDate = gmdate("Y-m-d H:i:s");

	if (!empty($bank)) {
	    
	     $bankDetailsId = $bank['id'];
	     $oldRouterNo = $bank['router_no'];
	     $oldAccountNo = $bank['account_no'];
	     $subMerchantId = $bank['sub_merchant_id'];
	     
	     if($oldRouterNo != $routerNo || $oldAccountNo != $accountNo){
		 
	/*	require_once 'Setup.php';
		Configuration::environment(BRAINTREE_ENVIRONMENT);
		Configuration::merchantId(BRAINTREE_MERCHANTID);
		Configuration::publicKey(BRAINTREE_PUBLICKEY);
		Configuration::privateKey(BRAINTREE_PRIVATEKEY); */

		require_once APIPATH . 'v1/braintree-php/lib/Braintree.php';

		Braintree_Configuration::environment(BRAINTREE_ENVIRONMENT);
		Braintree_Configuration::merchantId(BRAINTREE_MERCHANTID);
		Braintree_Configuration::publicKey(BRAINTREE_PUBLICKEY);
		Braintree_Configuration::privateKey(BRAINTREE_PRIVATEKEY);
		
		$result = Braintree_MerchantAccount::update(
		    $subMerchantId,
		    [
			'funding' => [
			    'accountNumber' => $accountNo,
			    'routingNumber' => $routerNo
			]
		    ]
		  );
		
		$inputArr = array('accountNumber'=>$accountNo,'routingNumber'=>$routerNo);
		$this->paymentlogs($userId,$inputArr,$result);

		  if ($result->success) {
		    $bankDetailsData = array('user_id'=>$userId,
			'account_type'=>$accountType,
			'router_no'=>$routerNo,
			'account_no'=>$accountNo,
			'modified'=> $currentDate,
		    );
		    $creditCardId = $this->updateBankDetails($bankDetailsData,$bankDetailsId);
		    
		    return SUCCESSFULLY_DONE;
		  }else{
		    $result = array('msg'=>$result->message);
		    return $result;
		  }
	     }else{
		    return SUCCESSFULLY_DONE;
	     }
	     
	} else {
	    
	    $select_user = "SELECT u.*,cl.name AS city_name,sl.state_code AS state_code,cil.name AS country_name FROM users AS u LEFT JOIN country_locales AS cl ON cl.`country_id` = u.`country_id` AND cl.`lang_code` = '$langCode' LEFT JOIN state_locales AS sl ON sl.`state_id` = u.`state_id` AND sl.`lang_code` = '$langCode' LEFT JOIN city_locales AS cil ON cil.`city_id` = u.`city_id` AND cil.`lang_code` = '$langCode' WHERE u.id = '{$userId}' LIMIT 1";  
	    
	    $user_res = mysql_query($select_user);
	    $user = mysql_fetch_assoc($user_res);
	    
	    if(!empty($user)){
		
		$firstName = $user['first_name'];
		$lastName = $user['last_name'];
		$email = $user['email'];
		$phone = $user['phone'];
		$dob = $user['dob'];
		$locality = $user['city_name'];
		$region = $user['state_code'];
		$streetAddress = $user['address'];
		$postalCode = $user['zip_code'];

		require_once APIPATH . 'v1/braintree-php/lib/Braintree.php';

		Braintree_Configuration::environment(BRAINTREE_ENVIRONMENT);
		Braintree_Configuration::merchantId(BRAINTREE_MERCHANTID);
		Braintree_Configuration::publicKey(BRAINTREE_PUBLICKEY);
		Braintree_Configuration::privateKey(BRAINTREE_PRIVATEKEY);
		
/*		require_once 'Setup.php';
		Configuration::environment(BRAINTREE_ENVIRONMENT);
		Configuration::merchantId(BRAINTREE_MERCHANTID);
		Configuration::publicKey(BRAINTREE_PUBLICKEY);
		Configuration::privateKey(BRAINTREE_PRIVATEKEY); */
		
		$merchantAccountParams = [
		    'individual' => [
		      'firstName' => $firstName,
		   //   'firstName' => 'approve_me',
		      'lastName' => $lastName,
		      'email' => $email,
		      'phone' => $phone,
		      'dateOfBirth' => $dob,
		      'address' => [
			  'streetAddress' => $streetAddress,
			  'locality' => $locality,
			  'region' => $region,
			  'postalCode' => $postalCode
		      ]
		    ],
		    'funding' => [
		//      'descriptor' => $firstName,
		      'destination' => 'bank',
		      'email' => $email,
		//      'mobilePhone' => '5555555555',
		      'accountNumber' => $accountNo,
		      'routingNumber' => $routerNo
		    ],
		    'tosAccepted' => true,
		    'masterMerchantAccountId' => BRAINTREE_MERCHANT,
		  //  'id' => "blue_ladders_store"
		];

	//	print_r($merchantAccountParams);die;

		$result = Braintree_MerchantAccount::create($merchantAccountParams);
		
		$this->paymentlogs($userId,$merchantAccountParams,$result);
		
		 if ($result->success) {
		    $subMerchantId = $result->merchantAccount->id;
		    $bankDetailsData = array('user_id'=>$userId,
			'account_type'=>$accountType,
			'router_no'=>$routerNo,
			'account_no'=>$accountNo,
			'created'=> $currentDate,
			'sub_merchant_id'=>$subMerchantId,
		    );
		    $creditCardId = $this->insertBankDetails($bankDetailsData);
		    return SUCCESSFULLY_DONE;
		  }else{
		    $result = array('msg'=>$result->message);
		    return $result;
		  }
	    }
	}
	return UNABLE_TO_PROCEED;
    }
    
     /**
     * This function used to get bank details
     * @params array $params
     * @return array 
     */
    public function getBankDetails($params = NULL) {

	$userId = (!empty($params['user_id']) ? $params['user_id'] : 0);
	
	$queryb = "SELECT * 
		    FROM `bank_details` as bd
		    WHERE bd.user_id = $userId";
	$resb = mysql_query($queryb);
	
	if (mysql_num_rows($resb) > 0) {
	    $response = array();
	    while ($array = mysql_fetch_assoc($resb)) {
		$response[] = $array;
	    }
	    return $response;
	} else {
	    return NO_RECORD_FOUND;
	}
	return UNABLE_TO_PROCEED;
    }

    //------ 01-12-2016 CREATED BY MANOJ -------//
    /**
     * This function used to check whether user has added any cc with his account
     * @params int $user_id user id
     * @return array 
     */
    public function isCardAdded($user_id) {
		$query = "SELECT * from credit_cards where user_id=$user_id";
		$res = mysql_query($query);
		if (mysql_num_rows($res) > 0) {
			return mysql_num_rows($res);
		} else {
		    return 'NO_RECORD_FOUND';
		}
    }
    
    
    public function payToDriverBank($param=NULL){
	$first_name = 'test';
	$last_name = 'test';
	$email = 'jspanawrbwr@gmail.com';
	$password = '123456';
	$global_setting = $this->getGlobalSetting();
		$from_email = $global_setting['from_email_text'] . '<' . $global_setting['from_email'] . '>';

		$sub = 'Register Successfully on Pida App';
		$headers = "From: $from_email\r\n";
		$headers .= "Reply-To: \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$message = "<html><head></head><body>
				<table><tr><td> Hello " . $first_name . " " . $last_name . ", </td></tr>
				<tr><td style='padding-top: 10px;'>Your are successfully registered on Pida App. </td></tr>  
				<tr><td>Email : " . $email . " </td></tr>
				<tr><td>Password : " . $password . " </td></tr>
				<tr><td style='padding-top: 20px;'>Thanks, </td></tr>
				<tr><td>Pida App </td></tr>        
				</table>
				</body></html>";

		$this->sendSMTPmail($email, $sub, $message);
		
	die;
	
	require_once 'Setup.php';
	Configuration::environment(BRAINTREE_ENVIRONMENT);
	Configuration::merchantId(BRAINTREE_MERCHANTID);
	Configuration::publicKey(BRAINTREE_PUBLICKEY);
	Configuration::privateKey(BRAINTREE_PRIVATEKEY);
	
	
//	$result = Braintree_Transaction::submitForSettlement('ehh4m8jd');
	
//	$result = Braintree_Transaction::holdInEscrow('ccr1b938');
	
//	$result = Braintree_Transaction::releaseFromEscrow("cencgjmx");
	
	$result = Braintree_MerchantAccount::find('hitu_singh_instant_kmss968f');
	
	echo '<pre>';
	print_r($result);
	die;
	
	$merchantAccountParams = [
  'individual' => [
    'firstName' => 'Jane',
    'lastName' => 'Doe',
    'email' => 'jane@14ladders.com',
    'phone' => '5553334444',
    'dateOfBirth' => '1981-11-19',
    'ssn' => '456-45-4567',
    'address' => [
      'streetAddress' => '111 Main St',
      'locality' => 'Chicago',
      'region' => 'IL',
      'postalCode' => '60622'
    ]
  ],
  'business' => [
    'legalName' => 'Jane\'s Ladders',
    'dbaName' => 'Jane\'s Ladders',
    'taxId' => '98-7654321',
    'address' => [
      'streetAddress' => '111 Main St',
      'locality' => 'Chicago',
      'region' => 'IL',
      'postalCode' => '60622'
    ]
  ],
  'funding' => [
    'descriptor' => 'Blue Ladders',
    'destination' => 'HDFC',
    'email' => 'funding@blueladders.com',
    'mobilePhone' => '5555555555',
    'accountNumber' => '1123581321',
    'routingNumber' => '071101307'
  ],
  'tosAccepted' => true,
  'masterMerchantAccountId' => "14ladders_marketplace",
  'id' => "blue_ladders_store"
];
$result = Braintree_MerchantAccount::create($merchantAccountParams);

$result = Braintree_MerchantAccount::update(
  'blue_ladders_store',
  [
    'individual' => ['firstName' => 'Jane']
  ]
);

if ($result->success) {
  echo 'Merchant account successfully updated';
} else {
  echo $result->errors;
}
	
	
	/*
	date_default_timezone_set('UTC');
	$updtd = date('Y-m-d H:i:s');
	
	$bookingId = $param['booking_id'];
	
	$bookingSql = "SELECT booking_locations.driver_arrived_source,bookings.id
			FROM booking_locations
			LEFT JOIN bookings ON booking_locations.booking_id = bookings.id
			WHERE bookings.id = $bookingId AND bookings.booking_status = 4";  // echo $bookingSql;die;
	
	$chkBooking = mysql_query($this->conn,$bookingSql);
	
	$flag = 0;
	if (mysql_num_rows($chkBooking) > 0) {
	    while ($array = mysql_fetch_assoc($chkBooking)) {
		if($flag ==0 && $array['driver_arrived_source']=='Y'){
		    $flag = 1;
		}
	    }
	    if($flag==0){
		 return BOOKING_COMPLETED_ARRIVED_ERROR;
	    }
	}
	
	$bookingSql = "SELECT bookings.price,credit_cards.token,transactions.id
			FROM bookings
			LEFT JOIN users ON bookings.user_id = users.id
			LEFT JOIN credit_cards ON users.credit_card_id = credit_cards.id
			LEFT JOIN transactions ON transactions.booking_id = bookings.id
			WHERE bookings.id = $bookingId AND bookings.booking_status = '4'"; 
	
	$chkBooking = mysql_query($this->conn,$bookingSql);
	$bookingData = mysql_fetch_assoc($chkBooking);
	$payment_method_token = $bookingData['token'];
	$amount = $bookingData['price'];
	$id = $bookingData['id'];
	
	if(!empty($amount) && !empty($payment_method_token) && empty($id)){ 

	    require_once 'Setup.php';
	    Configuration::environment(BRAINTREE_ENVIRONMENT);
	    Configuration::merchantId(BRAINTREE_MERCHANTID);
	    Configuration::publicKey(BRAINTREE_PUBLICKEY);
	    Configuration::privateKey(BRAINTREE_PRIVATEKEY);

	    $arr = [
		'amount' => $amount,
		'options' => [
		    'submitForSettlement' => True
		]
	    ];
	    if ($payment_method_token) {
		$arr['paymentMethodToken'] = $payment_method_token;
	    } else {
		$arr['paymentMethodNonce'] = $payment_method_nonce;
	    }
	    $result = Braintree_Transaction::sale($arr);
	    
	    $this->paymentlogs($bookingId,$arr,$result);
	  
	    if ($result->success) {

		

		$transaction = $result->transaction;
		$transaction_id = $transaction->id;
		
		$pidaCommission = PIDA_COMMISSION;
		$adminAmt = $amount * $pidaCommission/100;
		$driveAmt = $amount - $adminAmt;

		$transactionData = array('booking_id' => $bookingId, 'transaction_id' => $transaction_id, 'created' => $updtd,'status'=>'1','driver_amount'=>$driveAmt,'admin_amount'=>$adminAmt,'admin_percent'=>$pidaCommission);
		$transaction_id = $this->insertTransactions($transactionData);
		
		$updateData = array('booking_status'=>'5','status'=>'3','transaction_id'=>$transaction_id);
		$this->updateBookings($updateData,$bookingId);
		
		$query = "SELECT b.*,u.device_token,u.device_type"
			. " FROM"
			. " bookings AS b"
			. " LEFT JOIN users AS u on b.user_id = u.id"
			. " WHERE b.id = $bookingId AND u.device_token != '';"; // echo $query;die;

		$res = mysql_query($this->conn, $query);

		if (mysql_num_rows($res) > 0) {
		    $response = array();
		    while ($array = mysql_fetch_assoc($res)) {
			$response[] = array(
			    'booking_id' => $array['id'],
			    'price' => $array['price'],
			    'device_token' => $array['device_token'],
			    'device_type' => $array['device_type'],
			);
			$this->pushNotification($response, BOOKING_PAYMENT_NOTIFY_USER);
		    }
		}
		
		$query = "SELECT b.*,d.device_token,d.device_type"
			. " FROM"
			. " bookings AS b"
			. " LEFT JOIN users AS d on b.driver_id = d.id"
			. " WHERE b.id = $bookingId AND d.device_token != '';"; //  echo $query;die;

		$res = mysql_query($this->conn, $query);

		if (mysql_num_rows($res) > 0) {
		    $response = array();
		    while ($array = mysql_fetch_assoc($res)) {
			$response[] = array(
			    'booking_id' => $array['id'],
			    'price' => $driveAmt,
			    'device_token' => $array['device_token'],
			    'device_type' => $array['device_type'],
			);
			$this->pushNotification($response, BOOKING_PAYMENT_NOTIFY_DRIVER);
		    }
		}
		return SUCCESSFULLY_DONE;
	    }else{
		$paymentStatus = 2;
		$rejectReason = $result->message;
		$pidaCommission = PIDA_COMMISSION;
	
		$transactionData = array('booking_id' => $bookingId, 'created' => $updtd,'status'=>'1','admin_percent'=>$pidaCommission,'payment_status'=>$paymentStatus,'reject_reason'=>$rejectReason);
		$transaction_id = $this->insertTransactions($transactionData);
		
		$updateData = array('booking_status'=>'6','transaction_id'=>$transaction_id);
		$this->updateBookings($updateData,$bookingId);
		
	    }
	}else{
	    return NO_RECORD_FOUND;
	}
	
	return UNABLE_TO_PROCEED; */
    }
    
     /**
     * This function used to save report a problem
     * @params int $driver_id driver id
     * @params int $booking_id booking id
     * @return array 
     */
    public function reportaProblem($data = array()) {
		$reportedat = date('Y-m-d H:i:s');
		$title = $data['title'];
		$description = $data['description'];
		$userId = $data['user_id'];
	    // set all creditcards to set default false	
	    $insert_report = "insert into report_problems set title='$title', description='$description', driverid='$userId', 
	    reportedat='$reportedat'";
	    if(mysql_query($insert_report)) {
	        return SUCCESSFULLY_DONE;
		} else {
			return UNABLE_TO_PROCEED;
		}
    }
    #################### API Functions End #########################
    
    
    public function sendBookingEmail($bookingId=NULL){

	$query = "SELECT `Booking`.`id` as booking_id,`Booking`.`booking_status` as booking_status, `Booking`.`booking_type` as booking_type, `Booking`.`total_miles` as booking_total_miles,`Booking`.`price` as booking_price,`User`.`email` as user_email, `User`.`first_name` as user_first_name, `User`.`last_name` as user_last_name, `User`.`phone` as user_phone,`Driver`.`email` as driver_email, `Driver`.`first_name` as driver_first_name, `Driver`.`last_name` as driver_last_name, `Driver`.`phone` as driver_phone,`Vehicle`.`plate_no` as vehicle_plate_no FROM `pida`.`bookings` AS `Booking` LEFT JOIN `pida`.`users` AS `User` ON (`User`.`id` = `Booking`.`user_id`) LEFT JOIN `pida`.`users` AS `Driver` ON (`Driver`.`id` = `Booking`.`driver_id`) LEFT JOIN `pida`.`vehicles` AS `Vehicle` ON (`Booking`.`vehicle_id` = `Vehicle`.`id`) WHERE `Booking`.`id` = $bookingId LIMIT 1";

	$option_status = array('1' => 'Open', '2' => 'Assign', '3' => 'Cancel', '4' => 'Arrived', '5' => 'Completed', '6' => 'Paid');
	$option_type = array('1' => 'Now', '2' => 'Scheduled');

	$res = mysql_query($query);
	if (mysql_num_rows($res) > 0) {
	    
	    
	    $riderEmail = $driverEmail = '';
	    
	    while ($variables = mysql_fetch_assoc($res)) {
//		echo '<pre>';
//		print_r($variables);

		if (!empty($variables['pickup_date']) && date('Y-m-d', strtotime($variables['pickup_date'])) != '1970-01-01') {
		    $variables['pickup_date'] = date(DATETIME_FORMAT, strtotime($variables['pickup_date']));
		} else {
		    $variables['pickup_date'] = '-';
		}

		$variables['booking_status'] = $option_status[$variables['booking_status']];
		$variables['booking_type'] = $option_type[$variables['booking_type']];
		
		$riderEmail = $variables['user_email'];
		$driverEmail = $variables['driver_email'];

		$locationArr = $this->getBookingLocationsArr(array('booking_id' => $variables['booking_id']));


		$bookingLocationArrHtml = '';

		$j = 1;
		foreach ($locationArr as $location) {

		    $bookingLocationArrHtml .= '<tr>
          <td align="left" valign="top" style="border-bottom:1px solid #eeeeee; border-top:1px solid #eeeeee;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="30" align="left" valign="top">&nbsp;</td>
                <td height="50" align="left" valign="middle" style="background-color:#fafafa; font-size:18px; text-transform:uppercase;">Booking Location ' . $j . '</td>
                <td width="30" align="left" valign="top">&nbsp;</td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td align="left" valign="top" height="10">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="font-size:12px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="30" height="30">&nbsp;</td>
                <td width="125" height="30" style="font-weight:bold;">Source Company:</td>
                <td width="134" height="30" style="color:#777777;">' . $location['source_company_name'] . '</td>
                <td width="20" height="30">&nbsp;</td>
                <td width="125" height="30" style="font-weight:bold;">Source Address:</td>
                <td width="134" height="30" style="color:#777777;">' . $location['source_address'] . '</td>
                <td width="30" height="30">&nbsp;</td>
              </tr>
              <tr>
                <td height="30">&nbsp;</td>
                <td height="30" style="font-weight:bold;">Destination Company:</td>
                <td height="30" style="color:#777777;">' . $location['destination_company_name'] . '</td>
                <td height="30">&nbsp;</td>
                <td height="30" style="font-weight:bold;">Destination Address:</td>
                <td height="30" style="color:#777777;">' . $location['destination_address'] . '</td>
                <td height="30">&nbsp;</td>
              </tr>
            </table></td>
        </tr>';
		}

		$variables['booking_location_array'] = $bookingLocationArrHtml;

		$variables['logo_url'] = APIURL . 'img/email_logo.png';

		$template = file_get_contents(APIPATH."v1/invoice_email.html");

		foreach ($variables as $key => $value) {
		    $template = str_replace('{{ ' . $key . ' }}', $value, $template);
		}
	    }
	    
	    
	    $global_setting = $this->getGlobalSetting();
	    $from_email = $global_setting['from_email_text'] . '<' . $global_setting['from_email'] . '>';


	    $sub = 'Booking Invoice Pida App';
	    $headers = "From: $from_email\r\n";
	    $headers .= "Reply-To: \r\n";
	    $headers .= "MIME-Version: 1.0\r\n";
	    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	    $message = $template;
	    
//	    echo $driverEmail;
//	    echo $headers;
//	    echo $message;
//	    
//	    $result = mail($driverEmail, $sub, $message, $headers);
//	    var_dump($result);
//	    
//	    die;
	    
//	    $driverEmail = 'jspanwarbwr@gmail.com';
//	    $riderEmail = 'jspanwarbwr@gmail.com';
	    
	    $this->sendSMTPmail($driverEmail, $sub, $message);
	    $this->sendSMTPmail($riderEmail, $sub, $message);
	    
	}
	
    }


    public function sendBookingRejectAdminEmail($bookingId=NULL,$driverId=NULL){

    	$query = "SELECT `Booking`.`id` AS booking_id,`Booking`.`booking_status` AS booking_status, `Booking`.`booking_type` AS booking_type, `Booking`.`total_miles` AS booking_total_miles,`Booking`.`price` AS booking_price,`User`.`email` AS user_email, `User`.`first_name` AS user_first_name, `User`.`last_name` AS user_last_name, `User`.`phone` AS user_phone,`Driver`.`email` AS driver_email, `Driver`.`first_name` AS driver_first_name, `Driver`.`last_name` AS driver_last_name, `Driver`.`phone` AS driver_phone,`br`.reject_reason
    	FROM booking_requests AS br
    	LEFT JOIN bookings AS Booking ON br.booking_id = Booking.id
    	LEFT JOIN users AS `User` ON br.user_id = `User`.id
    	LEFT JOIN users AS Driver ON br.driver_id = Driver.id
    	WHERE br.booking_id = $bookingId AND br.driver_id = $driverId AND br.status = '2' LIMIT 1;";
    	$option_type = array('1' => 'Now', '2' => 'Scheduled');
    	$variables = $this->getSqlFirstArr($query);

    	if (!empty($variables)) {

    		$riderEmail = $driverEmail = '';

    		if (!empty($variables['pickup_date']) && date('Y-m-d', strtotime($variables['pickup_date'])) != '1970-01-01') {
    			$variables['pickup_date'] = date(DATETIME_FORMAT, strtotime($variables['pickup_date']));
    		} else {
    			$variables['pickup_date'] = '-';
    		}
    		
    		$variables['booking_type'] = $option_type[$variables['booking_type']];

    		$riderEmail = $variables['user_email'];
    		$driverEmail = $variables['driver_email'];

    		$variables['logo_url'] = APIURL . 'img/email_logo.png';

    		$template = file_get_contents(APIPATH."v1/driver_reject_admin_email.html");

    		foreach ($variables as $key => $value) {
    			$template = str_replace('{{ ' . $key . ' }}', $value, $template);
    		}
    	//	echo $template;die;

    		$global_setting = $this->getGlobalSetting();
    		$from_email = $global_setting['from_email_text'] . '<' . $global_setting['from_email'] . '>';
    		$to_email = $global_setting['to_email'];
    	//	$to_email = 'pida@mailinator.com';

    		$sub = 'Booking Reject Pida App';
    		$headers = "From: $from_email\r\n";
    		$headers .= "Reply-To: \r\n";
    		$headers .= "MIME-Version: 1.0\r\n";
    		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    		$message = $template;
    		$this->sendSMTPmail($to_email, $sub, $message);

    	}

    }
    
    public function getCurrentTripDetails($user_id, $lang){
    	$select_booking = "SELECT * from bookings where (user_id = $user_id or driver_id = $user_id) and booking_status IN('1','2','4') order by id desc LIMIT 1";
    	$select_booking = mysql_query($select_booking);
    	$select_booking = mysql_fetch_array($select_booking);
    	if(!empty($select_booking)){

			$langCode = (!empty($lang) ? $lang : DEFAULT_LANGUAGE);

			$id = $select_booking['id'];
			$query = "SELECT
						 b.id,
						 IFNULL(b.pickup_date,'') pickup_date, 
						 IFNULL(d.id,'') driver_id, 
						 IFNULL(vt.image,'') vehicle_type_image, 
						 IFNULL(d.first_name,'') first_name, 
						 IFNULL(d.last_name,'') last_name, 
						 IFNULL(d.phone,'') phone, 
						 IFNULL(d.photo,'') photo, 
						 IFNULL(u.device_type,'') device_type, 
						 IFNULL(u.device_token,'') device_token, 
						 IFNULL(vtl.name,'') name, 
						 IFNULL(v.plate_no,'') plate_no
					  FROM
					    bookings AS b
					    LEFT JOIN users AS u
					      ON b.user_id = u.id
					    LEFT JOIN users AS d
					      ON b.driver_id = d.id
					    LEFT JOIN vehicles AS v
					      ON b.vehicle_id = v.id
					    LEFT JOIN vehicle_types AS vt
					      ON b.`vehicle_type_id` = vt.`id`  
					    LEFT JOIN vehicle_type_locales AS vtl
					      ON b.`vehicle_type_id` = vtl.`vehicle_type_id`
					      AND vtl.lang_code = '$langCode'  
					  WHERE b.id = $id AND u.device_token != '';"; //  echo $query;die;

			$res = mysql_query($query);
			$array = mysql_fetch_array($res);
			$image_thumb = $image_large = $vehicle_type_image = '';
			if ($array['photo'] != '') {
			    if (file_exists(USER_PHOTO_PATH_THUMB . $array['photo'])) {
				$image_thumb = USER_PHOTO_URL_THUMB . $array['photo'];
			    }
			    if (file_exists(USER_PHOTO_PATH_LARGE . $array['photo'])) {
				$image_large = USER_PHOTO_URL_LARGE . $array['photo'];
			    }
			}
			
			if ($array['vehicle_type_image'] != '') {
				if (file_exists(VEHICLE_TYPE_IMG_PATH_THUMB . $array['vehicle_type_image'])) {
				   $vehicle_type_image = VEHICLE_TYPE_IMG_URL_THUMB . $array['vehicle_type_image'];
				}
			}			


    		$response[] = array(
			    'booking_id' => $array['id'],
			    'first_name' => $array['first_name'],
			    'last_name' => $array['last_name'],
			    'phone' => $array['phone'],
			    'image_thumb' => $image_thumb,
			    'image_large' => $image_large,
			    'vechicle_type_name'=> $array['name'],
			    'plate_no'=> $array['plate_no'],
			    'pickup_date' => $array['pickup_date'],
			    'device_token' => $array['device_token'],
			    'device_type' => $array['device_type'],
			    'driver_id' => $array['driver_id'],
			    'vehicle_type_image' => $vehicle_type_image,
			);
			return $response;
    	}else{
    		return NO_RECORD_FOUND;
    	}
    }   
    
    public function getDriverAssignTripDetails($user_id, $lang){
	$select_booking = "SELECT brt.id as brt_id FROM booking_request_temps as brt LEFT JOIN bookings as b on brt.booking_id=b.id  WHERE brt.driver_id = '{$user_id}' order by brt.id desc LIMIT 1";
	$select_booking = mysql_query($select_booking);
	$select_booking = mysql_fetch_array($select_booking);
	if (!empty($select_booking)) {

	    $langCode = (!empty($lang) ? $lang : DEFAULT_LANGUAGE);

	    $id = $select_booking['brt_id'];
	    $query = "SELECT brt.id,brt.booking_id,b.price,b.pickup_lat,b.pickup_long,b.total_miles,b.price,b.dimension,b.cubicfeet,u.first_name,u.last_name,u.photo,u.phone,d.device_type,d.device_type,d.device_token,IFNULL(b.cargo_type_notes,'') cargo_type_notes,IFNULL(b.delivery_type_notes,'') delivery_type_notes,IFNULL(b.lbh_dimension,'') lbh_dimension,IFNULL(d.lang_code, '" . $langCode . "') lang_code
					FROM
					  booking_request_temps AS brt
					  LEFT JOIN users AS u
					    ON brt.user_id = u.id
					  LEFT JOIN users AS d
					    ON brt.driver_id = d.id
					  LEFT JOIN bookings AS b
					    ON brt.booking_id = b.id
					WHERE brt.id = $id AND d.device_token != '';"; //  echo $query;die;

	    $res = mysql_query($query);

	    if (mysql_num_rows($res) > 0) {
		$response = array();
		while ($array = mysql_fetch_assoc($res)) {

		    $image_thumb = $image_large = '';
		    if ($array['photo'] != '') {
			if (file_exists(USER_PHOTO_PATH_THUMB . $array['photo'])) {
			    $image_thumb = USER_PHOTO_URL_THUMB . $array['photo'];
			}
			if (file_exists(USER_PHOTO_PATH_LARGE . $array['photo'])) {
			    $image_large = USER_PHOTO_URL_LARGE . $array['photo'];
			}
		    }
		    $bookingAddress = $this->getBookingLocationsArr(array('booking_id' => $array['booking_id']));
		    
		    $bookingCargoTypes = $this->getBookingCargoTypesArr(array('booking_id' => $array['booking_id'],'lang_code'=>$array['lang_code']));
		    $bookingDeliveryTypes = $this->getBookingDeliveryTypesArr(array('booking_id' => $array['booking_id'],'lang_code'=>$array['lang_code']));

		    $response[] = array(
			'booking_id' => $array['booking_id'],
			'price' => $array['price'],
			'first_name' => $array['first_name'],
			'last_name' => $array['last_name'],
			'pickup_lat' => $array['pickup_lat'],
			'pickup_long' => $array['pickup_long'],
			'image_thumb' => $image_thumb,
			'image_large' => $image_large,
			'booking_cargo_types' => $bookingCargoTypes,
			'booking_delivery_types' => $bookingDeliveryTypes,
			'cargo_type_notes' => $array['cargo_type_notes'],
			'delivery_type_notes' => $array['delivery_type_notes'],
			'booking_address' => $bookingAddress,
			'total_miles' => $array['total_miles'],
			'price' => $array['price'],
			'dimension' => $array['dimension'],
			'cubicfeet' => $array['cubicfeet'],
			'phone' => $array['phone'],
			'device_token' => $array['device_token'],
			'device_type' => $array['device_type'],
			'lbh_dimension' => $array['lbh_dimension'],
		    );
		}

		return $response;
	    }
	}

	return NO_RECORD_FOUND;
    }   


    public function get_driver_status($user_id = NULL) {
    	$ddQuery = "SELECT is_online,is_on_duty from driver_details where user_id = $user_id LIMIT 1";
    	$ddResult = mysql_query($ddQuery);
    	$ddArr = mysql_fetch_array($ddResult);

    	$response = array(
    		'driver_id' => $user_id,
    		'is_online' => 'N',
    	);

    	if(!empty($ddArr)){
    		if($ddArr['is_online']=='Y'){
    			$response = array(
    				'driver_id' => $user_id,
    				'is_online' => 'Y',
    			);
    		}else{
    			$response = array(
    				'driver_id' => $user_id,
    				'is_online' => 'N',
    			);
    		}	
    	}
    	return $response;
    }
    
    
    
    
}
