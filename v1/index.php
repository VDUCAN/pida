<?php 

//echo 'test'; exit;
date_default_timezone_set("UTC");
//ini_set("display_errors", "1");
//error_reporting(E_ALL);
require_once '../include/DbHandler.php';
require_once '../include/PassHash.php';
require '../lib/Slim/Slim.php';
require_once '../include/Pusher.php';
 require_once '../lib/Cake/Core/App.php';
  require_once '../lib/Cake/Utility/File.php';
 require_once '../lib/Cake/Utility/Folder.php';
// App::uses('Folder', 'Utility');
// App::uses('File', 'Utility');
//use Cake\Filesystem\File;
//use Braintree\Configuration;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$user_id = NULL;
$lang_code = NULL;

/**
 * Pusher
 * url - /pusher
 * method - POST
 * Param = userid(mandatory)
 */
$app->post('/pusher', function() use ($app) {
    $db = new DbHandler();
    $response = array();
    $channel_name = $app->request()->post('channel_name');
    $socket_id = $app->request()->post('socket_id');
								//(Key , secretKey ,app_id )
		$pusher = new Pusher('b1ded192640d8c8f6413', '928a292ab7e03b487a58', '278577');
		$reply = $pusher->socket_auth($channel_name, $socket_id);
    // echo $reply;
    $auth = json_decode($reply,1);
    $response["auth"] = $auth['auth'];
	echoRespnse(200, $response);
    die;
});


/**
 * Adding Middle Layer to authenticate every request
 * Checking if the request has valid api key in the 'Authorization' header
 */
function authenticate(\Slim\Route $route) {
   

 
    $app = \Slim\Slim::getInstance();
    
    $realm = 'Protected APIS';
 
    $req = $app->request();
    $res = $app->response();

    $username = $req->headers('PHP_AUTH_USER');
    $password = $req->headers('PHP_AUTH_PW');
    
    
     
    if (isset($username) && $username != '' && isset($password) && $password != '') {

      
	$db = new DbHandler();

	if ($userid = $db->validateUser($username, $password)) {
	    global $user_id;
		global $lang_code;
	    $user_id = $userid["id"];
		$lang_code = $userid["lang_code"];
	    return true;
	} else {
	    $res->header('WWW-Authenticate', sprintf('Basic realm="%s"', $realm));
	    $res = $app->response();
	    $res->status(401);
	    $app->stop();
	}
	
    } else {
      
	$res->header('WWW-Authenticate', sprintf('Basic realm="%s"', $realm));
	$res = $app->response();
	$res->status(401);
	$app->stop();
    }
}

/**
 * Verifying required params posted or not
 */
function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
	$app = \Slim\Slim::getInstance();
	parse_str($app->request()->getBody(), $request_params);
    }

    foreach ($required_fields as $field) {
	if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
	    $error = true;
	    $error_fields .= $field . ', ';
	}
    }

    if ($error) {
	// Required field(s) are missing or empty
	// echo error json and stop the app
	$response = array();
	$app = \Slim\Slim::getInstance();
	$response["code"] = 10;
	$response["error"] = true;
	$response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
	echoRespnse(400, $response);
	$app->stop();
    }
}

/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}

/**
 * Validating email address
 */
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	$response["code"] = 11;
	$response["error"] = true;
	$response["message"] = 'Email address is not valid';
	echoRespnse(200, $response);
	$app->stop();
    }
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
 * Customer Registration
 * url - /customer_registration
 * method - POST
 * params - first_name(mandatory), last_name(mandatory), phone(mandatory), email(mandatory), password(mandatory)
 */
$app->post('/customer_registration', function () use ($app) {
    // check for required params
    verifyRequiredParams(array('email', 'first_name', 'last_name', 'phone', 'password'));

    $response = array();

    // reading post params
    $data['email'] = $app->request->post('email');
    $data['password'] = $app->request->post('password');
    $data['first_name'] = $app->request->post('first_name');
    $data['last_name'] = $app->request->post('last_name');
    $data['phone'] = $app->request->post('phone');


    validateEmail($data['email']);
    $db = new DbHandler();

    $res = $db->customerRegistration($data);
    if ($res == 'UNABLE_TO_PROCEED') {
	$response["code"] = 1;
	$response["error"] = true;
	$response["message"] = "Unable to proceed";
	echoRespnse(200, $response);
    } else if ($res == 'EMAIL_ALREADY_EXISTED') {
	$response["code"] = 2;
	$response["error"] = true;
	$response["message"] = "Email already exists";
	echoRespnse(200, $response);
    } else if ($res == 'PHONE_ALREADY_EXISTED') {
	$response["code"] = 16;
	$response["error"] = true;
	$response["message"] = "Phone number already exists";
	echoRespnse(200, $response);
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = "Customer successfully registered";
	$response["data"] = $res;
	echoRespnse(201, $response);
    }
});


/**
 * Driver Registration
 * url - /driver_registration
 * method - POST
 * params - first_name(mandatory), last_name(mandatory), phone(mandatory), email(mandatory), password(mandatory), ssn(mandatory), driving_license_no(mandatory), country_id(mandatory), state_id(mandatory)
 */
$app->post('/driver_registration', function () use ($app) {
    // check for required params
    verifyRequiredParams(array('email', 'first_name', 'last_name', 'phone', 'password'));

    $response = array();

    // reading post params
    $data['email'] = $app->request->post('email');
    $data['password'] = $app->request->post('password');
    $data['first_name'] = $app->request->post('first_name');
    $data['last_name'] = $app->request->post('last_name');
    $data['phone'] = $app->request->post('phone');
//    $data['ssn'] = $app->request->post('ssn');
//    $data['driving_license_no'] = $app->request->post('driving_license_no');
//    $data['country_id'] = $app->request->post('country_id');
//    $data['state_id'] = $app->request->post('state_id');

    validateEmail($data['email']);
    $db = new DbHandler();

    $res = $db->driverRegistration($data);
    if ($res == 'UNABLE_TO_PROCEED') {
	$response["code"] = 1;
	$response["error"] = true;
	$response["message"] = "Unable to proceed";
	echoRespnse(200, $response);
    } else if ($res == 'EMAIL_ALREADY_EXISTED') {
	$response["code"] = 2;
	$response["error"] = true;
	$response["message"] = "Email already exists";
	echoRespnse(200, $response);
    } else if ($res == 'PHONE_ALREADY_EXISTED') {
	$response["code"] = 3;
	$response["error"] = true;
	$response["message"] = "Phone number already exists";
	echoRespnse(200, $response);
  /*  } else if ($res == 'SSN_ALREADY_EXISTED') {
	$response["code"] = 4;
	$response["error"] = true;
	$response["message"] = "SSN already exists";
	echoRespnse(200, $response);
    } else if ($res == 'DRIVING_LICENSE_ALREADY_EXISTED') {
	$response["code"] = 5;
	$response["error"] = true;
	$response["message"] = "Driving license number already exists";
	echoRespnse(200, $response); */
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = "Driver successfully registered";
	$response["data"] = $res;
	echoRespnse(201, $response);
    }
});


/**
 * Validate Driver Registration
 * url - /validate_driver_registration
 * method - POST
 * params - first_name(mandatory), last_name(mandatory), phone(mandatory), email(mandatory), password(mandatory)
 */
$app->post('/validate_driver_registration', function () use ($app) {
    // check for required params
	verifyRequiredParams(array('email', 'first_name', 'last_name', 'phone', 'password'));
	$response = array();
    // reading post params
	$data['email'] = $app->request->post('email');
	$data['password'] = $app->request->post('password');
	$data['first_name'] = $app->request->post('first_name');
	$data['last_name'] = $app->request->post('last_name');
	$data['phone'] = $app->request->post('phone');
	validateEmail($data['email']);
	$db = new DbHandler();
	$res = $db->validateDriverRegistration($data);
	if ($res == 'UNABLE_TO_PROCEED') {
		$response["code"] = 1;
		$response["error"] = true;
		$response["message"] = "Unable to proceed";
		echoRespnse(200, $response);
	} else if ($res == 'EMAIL_ALREADY_EXISTED') {
		$response["code"] = 2;
		$response["error"] = true;
		$response["message"] = "Email already exists";
		echoRespnse(200, $response);
	} else if ($res == 'PHONE_ALREADY_EXISTED') {
		$response["code"] = 3;
		$response["error"] = true;
		$response["message"] = "Phone number already exists";
		echoRespnse(200, $response);
	} else {
		$response["code"] = 0;
		$response["error"] = false;
		$response["message"] = "Driver validate successfully";
		$response["data"] = $res;
		echoRespnse(201, $response);
	}
});

/**
 * Driver Registration
 * url - /driver_registration
 * method - POST
 * params - ssn(mandatory), driving_license_no(mandatory), country_id(mandatory), state_id(mandatory)
 * params - vehicle {"vehicle_type_id":"1","make_id":"1","model_id":"1","make_year":"2013","color":"red","plate_no":"12353"}
 * params - question [{"question":"test question","answer":"test answer"},{"question":"test question","answer":"test answer"},{"question":"test question","answer":"test answer"}]
 */
$app->post('/driver_set_profile', function () use ($app) {
    // check for required paramssss
    
    verifyRequiredParams(array('driver_id', 'ssn', 'driving_license_no', 'country_id', 'state_id','vehicle_type_id','make_id','model_id','make_year','color','plate_no','question','dob'));

    $response = array();

    // reading post params
    $data['ssn'] = $app->request->post('ssn');
    $data['driving_license_no'] = $app->request->post('driving_license_no');
    $data['country_id'] = $app->request->post('country_id');
    $data['state_id'] = $app->request->post('state_id');
    $data['question'] = $app->request->post('question');
    
    $data['vehicle_type_id'] = $app->request->post('vehicle_type_id');
    $data['make_id'] = $app->request->post('make_id');
    $data['model_id'] = $app->request->post('model_id');
    $data['make_year'] = $app->request->post('make_year');
    $data['color'] = $app->request->post('color');
    $data['plate_no'] = $app->request->post('plate_no');
    $data['driver_id'] = $app->request->post('driver_id');
    $data['dob'] = $app->request->post('dob');
    
    $errorMsg = array();
    if (isset($_FILES['insurance_policy_doc']) && $_FILES['insurance_policy_doc'] != '') {
	$data['insurance_policy_doc'] = $_FILES['insurance_policy_doc'];
    } else {
	$errorMsg[] = 'insurance_policy_doc';
    }
    
    if (isset($_FILES['registration_doc']) && $_FILES['registration_doc'] != '') {
	$data['registration_doc'] = $_FILES['registration_doc'];
    } else {
	$errorMsg[] = 'registration_doc';
    }
    
    if (isset($_FILES['driving_license_doc']) && $_FILES['driving_license_doc'] != '') {
	$data['driving_license_doc'] = $_FILES['driving_license_doc'];
    } else {
//	$errorMsg[] = 'driving_license_doc';
    }
 
    if(!empty($errorMsg)){
	$errorFields = implode(',', $errorMsg);
	$msg = "Required field(s) $errorFields is missing or empty";
	$response["code"] = 0;
	$response["error"] = true;
	$response["message"] = $msg;
	$response["data"] = array();
	echoRespnse(200, $response);
	die;
    }

    $db = new DbHandler();
    $res = $db->driverSetProfile($data); 
    if ($res == 'UNABLE_TO_PROCEED') {
	$response["code"] = 1;
	$response["error"] = true;
	$response["message"] = "Unable to proceed";
	$response["data"] = array();
	echoRespnse(200, $response);
    } else if ($res == 'SSN_ALREADY_EXISTED') {
	$response["code"] = 4;
	$response["error"] = true;
	$response["message"] = "SSN already exists";
	$response["data"] = array();
	echoRespnse(200, $response);
    } else if ($res == 'DRIVING_LICENSE_ALREADY_EXISTED') {
	$response["code"] = 5;
	$response["error"] = true;
	$response["message"] = "Driving license number already exists";
	$response["data"] = array();
	echoRespnse(200, $response);
    } else if ($res == 'INSURANCE_POLICY_DOC_SIZE') {
	$response["code"] = INSURANCE_POLICY_DOC_SIZE;
	$response["error"] = true;
	$response["message"] = INSURANCE_POLICY_DOC_SIZE_MSG;
	$response["data"] = array();
	echoRespnse(200, $response);
    } else if ($res == 'INSURANCE_POLICY_DOC_TYPE') {
	$response["code"] = INSURANCE_POLICY_DOC_TYPE;
	$response["error"] = true;
	$response["message"] = INSURANCE_POLICY_DOC_TYPE_MSG;
	$response["data"] = array();
	echoRespnse(200, $response);
    } else if ($res == 'REGISTRATION_DOC_SIZE') {
	$response["code"] = REGISTRATION_DOC_SIZE;
	$response["error"] = true;
	$response["message"] = REGISTRATION_DOC_SIZE_MSG;
	$response["data"] = array();
	echoRespnse(200, $response);
    } else if ($res == 'REGISTRATION_DOC_TYPE') {
	$response["code"] = REGISTRATION_DOC_TYPE;
	$response["error"] = true;
	$response["message"] = REGISTRATION_DOC_TYPE_MSG;
	$response["data"] = array();
	echoRespnse(200, $response);
    } else if ($res == 'LICENSE_DOC_SIZE') {
	$response["code"] = LICENSE_DOC_SIZE;
	$response["error"] = true;
	$response["message"] = LICENSE_DOC_SIZE_MSG;
	$response["data"] = array();
	echoRespnse(200, $response);
    } else if ($res == 'LICENSE_DOC_TYPE') {
	$response["code"] = LICENSE_DOC_TYPE;
	$response["error"] = true;
	$response["message"] = LICENSE_DOC_TYPE_MSG;
	$response["data"] = array();
	echoRespnse(200, $response);
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = "Driver successfully registered";
	$response["data"] = $res;
	echoRespnse(201, $response);
    } 
});


$app->post('/deduct_booking_payment', 'authenticate', function() use ($app) {
    global $user_id;
  	global $lang_code;
	$lang_code = 'en';
    $db = new DbHandler();
    
    // check for required params
    verifyRequiredParams(array('booking_id'));
    

    $response = array();
    // reading post params
    $bookingId 		= $app->request()->post('booking_id');
	$travel_route   = $app->request()->post('travel_route');
	

    $bookingData = array('booking_id' => $bookingId,'travel_route' => $travel_route);

    $response = array();
    
	$res = $db->deduct_booking_payment($bookingData);//new function added by Hassan

     if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($res == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response['data'] = array();
    } else if ($res == BOOKING_CANCEL_ERROR) {
	$response['code'] = BOOKING_CANCEL_ERROR;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "BOOKING_CANCEL_ERROR");
	$response['data'] = array();
    } else if ($res == BOOKING_COMPLETED_ARRIVED_ERROR) {
	$response['code'] = BOOKING_COMPLETED_ARRIVED_ERROR;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "BOOKING_COMPLETED_ARRIVED_ERROR");
	$response['data'] = array();
    } else if ($res == INVALID_DRIVER_BANK_ACCOUNT) {
	$response['code'] = INVALID_DRIVER_BANK_ACCOUNT;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "INVALID_DRIVER_BANK_ACCOUNT");
	$response['data'] = array();
    }else {
		if(isset($res['msg']) && !empty($res['msg'])){
			$errorMsg = $res['msg'];
			$response['code'] = INVALID_BANK_ACCOUNT;
			$response['error'] = false;
			$response['message'] = $errorMsg;
			$response['data'] = array();
		}
		else{
			$response["code"] = 0;
			$response["error"] = false;
			$response["message"] = getMessage($lang_code, "PAYMENT_DEDUCT");
			$response['data'] = array();
		}	
    }
    echoRespnse(200, $response);
});
/**
 * User Login
 * url - /login
 * method - POST
 * params - name(optional), email (mandatory) ,password (optional),login_from(mandatory),device_token(mandatory),user_type(mandatory),
 * login_from  -   N -> normal, F -> facebook, G -> google+, T -> Twitter
 * user_type - N=Normal User(Customer), D=Driver
 * device_type - A=Android, I=IOS
 */
$app->post('/login', function() use ($app) {


    // check for required params
    verifyRequiredParams(array('email', 'login_from', 'device_type', 'device_token', 'user_type'));
    $response = array();

    // reading post params
    $data['email'] = $app->request->post('email');
    $data['login_from'] = $app->request->post('login_from');
    $data['password'] = $app->request->post('password');
    $data['device_type'] = $app->request->post('device_type');
    $data['device_token'] = $app->request->post('device_token');
    $data['user_type'] = $app->request->post('user_type');
    $data['name'] = $app->request->post('name');
    $data['lang_code'] = $app->request->post('lang_code');
   
    $db = new DbHandler();
    $res = $db->loginUser($data);
    if ($res == INVALID_REQUEST) {
	$response["code"] = 1;
	$response["error"] = true;
	$response["message"] = "Invalid Login";
    } else if ($res == NEED_PASSWORD) {
	$response["code"] = 2;
	$response["error"] = true;
	$response["message"] = "Need password for login from App";
    } else if ($res == INVALID_EMAIL) {
	$response["code"] = 3;
	$response["error"] = true;
	$response["message"] = "Invalid Email Id";
    } else if ($res == INVALID_EMAIL_PASSWORD) {
	$response["code"] = 4;
	$response["error"] = true;
	$response["message"] = "Invalid Email Id or Password";
    } else if ($res == UNABLE_TO_PROCEED) {
	$response["code"] = 6;
	$response["error"] = true;
	$response["message"] = "Unable to proceed your request";
    } else if ($res == EMAIL_ALREADY_EXISTED) {
	$response["code"] = 7;
	$response["error"] = true;
	$response["message"] = "Email already exist";
    } else if ($res == USER_ACCOUNT_DEACTVATED) {
	$response["code"] = 8;
	$response["error"] = true;
	$response["message"] = "User account deactivated";
    } else if ($res == DRIVER_ACCOUNT_DEACTVATED) {
	$response["code"] = DRIVER_ACCOUNT_DEACTVATED;
	$response["error"] = true;
	$response["message"] = DRIVER_ACCOUNT_DEACTVATED_MSG;
    }else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = "User Profile";
	$response['data']['user_id'] = $res['id'];
	$response['data']['first_name'] = $res['first_name'];
	$response['data']['last_name'] = (!empty($res['last_name']) ? $res['last_name'] : '');
	$response['data']['email'] = $res['email'];
	$response['data']['phone'] = $res['phone'];
	$response['data']['password'] = $res['password'];
	$response['data']['customer_status'] = $res['customer_status'];
	$response['data']['driver_status'] = $res['driver_status'];
	$response['data']['login_from'] = $res['login_from'];
	$response['data']['user_type'] = $data['user_type'];
	$response['data']['last_login'] = (!empty($res['last_login']) ? $res['last_login'] : '');
	$response['data']['lang_code'] = $res['lang_code'];
	$response['data']['photo_thumb'] = $response['data']['photo_large'] = '';
	$response['data']['dob'] = (!empty($res['dob']) ? $res['dob'] : '');
	$response['data']['country_name'] = (!empty($res['country_name']) ? $res['country_name'] : '');
	$response['data']['state_name'] = (!empty($res['state_name']) ? $res['state_name'] : '');
	$response['data']['city_name'] = (!empty($res['city_name']) ? $res['city_name'] : '');
	$response['data']['address'] = (!empty($res['address']) ? $res['address'] : '');
	$response['data']['zip_code'] = (!empty($res['zip_code']) ? $res['zip_code'] : '');

	if ('D' == $data['user_type']) {

	    $response['data']['ssn'] = $response['data']['driving_license_no'] = $response['data']['is_online'] = $response['data']['is_on_duty'] = '';
	    if (isset($res['DriverDetail']) && !empty($res['DriverDetail'])) {
		$response['data']['ssn'] = (!empty($res['DriverDetail']['ssn']) ? $res['DriverDetail']['ssn'] : '');
		$response['data']['driving_license_no'] = (!empty($res['DriverDetail']['driving_license_no']) ? $res['DriverDetail']['driving_license_no'] : '');
		$response['data']['is_online'] = $res['DriverDetail']['is_online'];
		$response['data']['is_on_duty'] = $res['DriverDetail']['is_on_duty'];
	    }
	}

	if ($res['photo'] != '') {
	    if (file_exists(USER_PHOTO_PATH_THUMB . $res['photo'])) {
		$response['data']['photo_thumb'] = USER_PHOTO_URL_THUMB . $res['photo'];
	    }
	    if (file_exists(USER_PHOTO_PATH_LARGE . $res['photo'])) {
		$response['data']['photo_large'] = USER_PHOTO_URL_LARGE . $res['photo'];
	    }
	}
	$response['data']['totcards'] = $res['totcards'];
	$response['data']['totbankaccount'] = $res['totbankaccount'];
    }
    echoRespnse(200, $response);
});

/**
 * User Forgot Password
 * url - /forgot_password
 * method - POST
 * params - email (mandatory)
 */
$app->post('/forgot_password', function () use ($app) {
    verifyRequiredParams(array('email'));

    $email = $app->request()->post('email');
    $response = array('data'=>array());

    $db = new DbHandler();
    $user = $db->sendNewPassword($email);
    if ($user == UNABLE_TO_PROCEED) {
	$response['code'] = 1;
	$response['error'] = true;
	$response['message'] = "Unable to proceed your request";
    } else if ($user == INVALID_EMAIL) {
	$response['code'] = 1;
	$response['error'] = true;
	$response['message'] = "Email address does not exist";
    } else {
	$response['code'] = 0;
	$response['error'] = false;
	$response['message'] = "New Password sent to your registered email address";
    }
    echoRespnse(200, $response);
});

/**
 * Get Categories
 * url - /get_categories/:lang
 * method - GET
 * params - lang (mandatory) -> en/es,
 */
$app->get('/get_categories/:lang', function($lang) use ($app) {

	$db = new DbHandler();
    $r = $db->getCategories($lang);
	
	$response = array(
	'code' => 1,
	'error' => true,
	'message' => getMessage($lang, "NO_RECORD_FOUND"),
	'data' => array()
    );
	
    if ('' != $r && UNABLE_TO_PROCEED != $r) {

	$response = array(
	    'code' => 0,
	    'error' => false,
	    'message' => getMessage($lang, "SUCCESSFULLY_DONE"),
	    'data' => $r
	);
    }

    echoRespnse(200, $response);
});


/**
 * Get Vehicle Types
 * url - /get_vehicle_types/:lang/:category_id
 * method - GET
 * params - lang (mandatory) -> en/es, category_id (mandatory)
 */
$app->get('/get_vehicle_types/:lang/:category_id', function($lang, $category_id) use ($app) {

    $db = new DbHandler();
    $r = $db->getVehicleTypes($lang, $category_id);
	
	$response = array(
	'code' => 1,
	'error' => true,
	'message' => getMessage($lang, "NO_RECORD_FOUND"),
	'data' => array()
    );
	
    if ('' != $r && UNABLE_TO_PROCEED != $r) {

	$response = array(
	    'code' => 0,
	    'error' => false,
	    'message' => getMessage($lang, "SUCCESSFULLY_DONE"),
	    'data' => $r
	);
    }

    echoRespnse(200, $response);
});

/**
 * Get Cargo Types
 * url - /get_cargo_types/:lang
 * method - GET
 * params - lang (mandatory) -> en/es,
 */
$app->get('/get_cargo_types/:lang', function($lang) use ($app) {

    $db = new DbHandler();
    $r = $db->getCargoTypes($lang);
    
	$response = array(
	'code' => 1,
	'error' => true,
	'message' => getMessage($lang, "NO_RECORD_FOUND"),
	'data' => array()
    );
	
	if ('' != $r && UNABLE_TO_PROCEED != $r) {

	$response = array(
	    'code' => 0,
	    'error' => false,
	    'message' => getMessage($lang, "SUCCESSFULLY_DONE"),
	    'data' => $r
	);
    }

    echoRespnse(200, $response);
});


/**
 * Get Countries
 * url - /get_countries/:lang
 * method - GET
 * params - lang (mandatory) -> en/es,
 */
$app->get('/get_countries/:lang', function($lang) use ($app) {

    $db = new DbHandler();
    $r = $db->getCountries($lang);
    
	$response = array(
	'code' => 1,
	'error' => true,
	'message' => getMessage($lang, "NO_RECORD_FOUND"),
	'data' => array()
    );
	
	if ('' != $r && UNABLE_TO_PROCEED != $r) {

	$response = array(
	    'code' => 0,
	    'error' => false,
	    'message' => getMessage($lang, "SUCCESSFULLY_DONE"),
	    'data' => $r
	);
    }

    echoRespnse(200, $response);
});

/**
 * Get States
 * url - /get_states/:lang/:country_id
 * method - GET
 * params - lang (mandatory) -> en/es, country_id (mandatory, if country_id is 0 then api will return all the states)
 */
$app->get('/get_states/:lang/:country_id', function($lang, $country_id) use ($app) {

    $db = new DbHandler();
    $r = $db->getStates($lang, $country_id);
    
	$response = array(
	'code' => 1,
	'error' => true,
	'message' => getMessage($lang, "NO_RECORD_FOUND"),
	'data' => array()
    );
	
	if ('' != $r && UNABLE_TO_PROCEED != $r) {

	$response = array(
	    'code' => 0,
	    'error' => false,
	    'message' => getMessage($lang, "SUCCESSFULLY_DONE"),
	    'data' => $r
	);
    }

    echoRespnse(200, $response);
});

/**
 * Get Cities
 * url - /get_cities/:lang/:state_id
 * method - GET
 * params - lang (mandatory) -> en/es, state_id (mandatory, if state_id is 0 then api will return all the cities)
 */
$app->get('/get_cities/:lang/:state_id', function($lang, $state_id) use ($app) {

    $db = new DbHandler();
    $r = $db->getCities($lang, $state_id);
    
	$response = array(
	'code' => 1,
	'error' => true,
	'message' => getMessage($lang, "NO_RECORD_FOUND"),
	'data' => array()
    );
	
	if ('' != $r && UNABLE_TO_PROCEED != $r) {

	$response = array(
	    'code' => 0,
	    'error' => false,
	    'message' => getMessage($lang, "SUCCESSFULLY_DONE"),
	    'data' => $r
	);
    }

    echoRespnse(200, $response);
});

/**
 * Get all active languages of application
 * url - /get_languages
 * method - GET
 */
$app->get('/get_languages', function() use ($app) {

    $db = new DbHandler();
    $r = $db->getLanguages();
    
	$response = array(
	'code' => 1,
	'error' => true,
	'message' => 'No Record Found',
	'data' => array()
    );
	
	if ('' != $r && UNABLE_TO_PROCEED != $r) {

	$response = array(
	    'code' => 0,
	    'error' => false,
	    'message' => 'Success',
	    'data' => $r
	);
    }

    echoRespnse(200, $response);
});

/**
 * User Change Password
 * url - /change_password
 * method - POST
 * params - old_password (mandatory), new_password (mandatory)
 * header Params - email (mandatory), password (mandatory)
 */
$app->post('/change_password', 'authenticate', function () use ($app) {
    global $user_id;
	global $lang_code;
    verifyRequiredParams(array('old_password', 'new_password'));

    $old_password = $app->request()->post('old_password');
    $new_password = $app->request()->post('new_password');

    $data = array(
	'user_id' => $user_id,
	'old_password' => $old_password,
	'new_password' => $new_password,
    );

    $response = array('data'=>array());
    $db = new DbHandler();
    $user = $db->changePassword($data);
    if ($user == UNABLE_TO_PROCEED) {
	$response['code'] = 1;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
    } else if ($user == INVALID_USER) {
	$response['code'] = 2;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "INVALID_USER");
    } else if ($user == INVALID_OLD_PASSWORD) {
	$response['code'] = 3;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "INVALID_OLD_PASSWORD");
    } else {
	$response['code'] = 0;
	$response['error'] = false;
	//$response['message'] = "Password successfully changed";
	$response['message'] = getMessage($lang_code, "PASSWORD_CHANGE_SUCCESS");
    }
    echoRespnse(200, $response);
});

/**
 * Get FAQ
 * url - /get_faq
 * method - GET
 * params - lang (mandatory) -> en/es
 */
$app->get('/get_faq/:lang', function($lang) use ($app) {

    
    $db = new DbHandler();
    $r = $db->getFaq($lang);
	
	$response = array(
	'code' => 1,
	'error' => true,
	'message' => getMessage($lang, "NO_RECORD_FOUND"),
	'data' => array()
    );

    if ('' != $r && UNABLE_TO_PROCEED != $r) {

	$response = array(
	    'code' => 0,
	    'error' => false,
	    'message' => getMessage($lang, "SUCCESSFULLY_DONE"),
	    'data' => $r
	);
    }

    echoRespnse(200, $response);
});

/**
 * Get Statcic Page
 * url - /get_static_page
 * method - GET
 * params - lang (mandatory) -> en/es, page_id (mandatory),
 */
$app->get('/get_static_page/:lang/:page_id', function($lang, $page_id) use ($app) {

    $db = new DbHandler();
    $r = $db->getStaticPage($lang, $page_id);
    
	$response = array(
	'code' => 1,
	'error' => true,
	'message' => getMessage($lang, "NO_RECORD_FOUND"),
	'data' => array()
    );

	if ('' != $r && UNABLE_TO_PROCEED != $r) {
	$response = array(
	    'code' => 0,
	    'error' => false,
	    'message' => getMessage($lang, "SUCCESSFULLY_DONE"),
	    'data' => $r
	);
    }

    echoRespnse(200, $response);
});

/**
 * Get Vehicle Makes
 * url - /get_vehicle_makes/:lang
 * method - GET
 * params - lang (mandatory) -> en/es,
 */
$app->get('/get_vehicle_makes/:lang', function($lang) use ($app) {

    $db = new DbHandler();
    $r = $db->getVehicleMakes($lang);
	
	$response = array(
	'code' => 1,
	'error' => true,
	'message' => getMessage($lang, "NO_RECORD_FOUND"),
	'data' => array()
    );

    if ('' != $r && UNABLE_TO_PROCEED != $r) {

	$response = array(
	    'code' => 0,
	    'error' => false,
	    'message' => getMessage($lang, "SUCCESSFULLY_DONE"),
	    'data' => $r
	);
    }

    echoRespnse(200, $response);
});

/**
 * Get Vehicle Models
 * url - /get_vehicle_models/:lang/:make_id
 * method - GET
 * params - lang (mandatory) -> en/es, make_id (mandatory, if make_id is 0 then api will return all the models)
 */
$app->get('/get_vehicle_models/:lang/:make_id', function($lang, $make_id) use ($app) {

    $db = new DbHandler();
    $r = $db->getVehicleModels($lang, $make_id);
    
	$response = array(
	'code' => 1,
	'error' => true,
	'message' => getMessage($lang, "NO_RECORD_FOUND"),
	'data' => array()
    );

	if ('' != $r && UNABLE_TO_PROCEED != $r) {

	$response = array(
	    'code' => 0,
	    'error' => false,
	    'message' => getMessage($lang, "SUCCESSFULLY_DONE"),
	    'data' => $r
	);
    }

    echoRespnse(200, $response);
});


/**
 * Update the current lat long of user
 * url - /update_user_location
 * method - POST
 * params - lat (mandatory), long (mandatory)
 * header Params - email (mandatory), password (mandatory)
 */
$app->post('/update_user_location', 'authenticate', function () use ($app) {
    global $user_id;
	global $lang_code;
    verifyRequiredParams(array('lat', 'long'));

    $data = array(
	'user_id' => $user_id,
	'lat' => $app->request()->post('lat'),
	'long' => $app->request()->post('long'),
	'booking_id' => $app->request()->post('booking_id')
    );
    $response = array('data'=>array());
    $db = new DbHandler();
    $user = $db->updateUserLocation($data);
    if ($user == UNABLE_TO_PROCEED) {
	$response['code'] = 1;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
    } else if ($user == INVALID_USER) {
	$response['code'] = 2;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "INVALID_USER");
    } else {
	$response['code'] = 0;
	$response['error'] = false;
	//$response['message'] = "Location successfully updated";
	$response['message'] = getMessage($lang_code, "LOCATION_UPDATE_SUCCESS");
    }
    echoRespnse(200, $response);
});

/**
 * Get Fare Calculation
 * url - /get_fare_calculation/:lang
 * method - GET
 * header Params - email (mandatory), password (mandatory)
 */
$app->get('/get_fare_calculation', 'authenticate', function() use ($app) {

    global $user_id;
	global $lang_code;
    $response = array(
	'code' => 1,
	'error' => true,
	'message' => getMessage($lang_code, "NO_RECORD_FOUND"),
	'data' => array()
    );

    $db = new DbHandler();
    
    $data = array(
	'lang_code' => $lang_code,
    );
    
    $r = $db->getFareCalculation($data);
    if ('' != $r && UNABLE_TO_PROCEED != $r) {
	$response = array(
	    'code' => 0,
	    'error' => false,
	    'message' => getMessage($lang_code, "SUCCESSFULLY_DONE"),
	    'data' => $r
	);
    }

    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * Get near by drivers
 * url - /get_near_by_drivers/
 * method - GET
 * params - lat (mandatory), long (mandatory) , miles (mandatory)
 * header Params - email (mandatory), password (mandatory)
 */
$app->get('/get_near_by_drivers', 'authenticate', function() use ($app) {

    global $user_id;
    $miles = (isset($_REQUEST['miles']) ? $_REQUEST['miles'] : NEAR_BY_MILES);
    $lat = (isset($_REQUEST['lat']) ? $_REQUEST['lat'] : 0);
    $long = (isset($_REQUEST['lat']) ? $_REQUEST['long'] : 0);
    $lang_code = (isset($_REQUEST['lang_code']) ? $_REQUEST['lang_code'] : DEFAULT_LANGUAGE);
    $params = array('user_id' => $user_id, 'miles' => $miles, 'lat' => $lat, 'long' => $long, 'lang_code' => $lang_code);
	
    $response = array(
	'code' => 1,
	'error' => true,
	'message' => getMessage($lang_code, "SEARCH_DRIVER"),
	'data' => array()
    );

    $db = new DbHandler();
    $r = $db->getNearByDrivers($params);
    if ('' != $r && UNABLE_TO_PROCEED != $r) {

	$response = array(
	    'code' => 0,
	    'error' => false,
	    'message' => getMessage($lang_code, "SUCCESSFULLY_DONE"),
	    'data' => $r
	);
    }

    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * Get delivery Types
 * url - /get_delivery_types/:lang
 * method - GET
 * params - lang (mandatory) -> en/es,
 * header Params - email (mandatory), password (mandatory)
 */
$app->get('/get_delivery_types/:lang/', 'authenticate', function($lang) use ($app) {

    global $user_id;
    $db = new DbHandler();
    $res = $db->getDeliveryTypes($lang);

    if ($res == NO_RECORD_FOUND) {
	$response["code"] = NO_RECORD_FOUND;
	$response["error"] = true;
	$response["message"] = getMessage($lang, "NO_RECORD_FOUND");
	$response["data"] = array();
    } elseif ($res == UNABLE_TO_PROCEED) {
	$response["code"] = UNABLE_TO_PROCEED;
	$response["error"] = true;
	$response["message"] = getMessage($lang, "UNABLE_TO_PROCEED");
	$response["data"] = array();
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang, "SUCCESSFULLY_DONE");
	$response["data"] = $res;
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});



$app->post('/testPn', function() use ($app) {

    $response = array();
    $db = new DbHandler();
	// $db->sendNotificationIphonePn('test', '67ac48abeae7cfafa00c97bf18d6e295943dacee3e2a5497879b4a2890028b32');
	if ($db->sendNotificationIphonePn('test', '2c8ce15954456480a0e8a8abde504692a46ecc0bc9764521815b138ca8eeac7d')) {
		echo 'sent';
	} else {
		echo 'not';
	}

});


$app->get('/testing', function() use ($app) {

    $response = array();
    $db = new DbHandler();

 /*   $price = 28.23;
    echo $db->getDriverAmt($price); */

 /*   $params = array('id'=>1350);
    $db->sendBookingPush($params,NULL); */
	
/*	$bookingId = 1350;
	if ($db->sendBookingEmail($bookingId)) {
		echo 'sent';
	} else {
		echo 'not';
	} */
	die;
});
/**
 * Book a driver
 * url - /book_driver
 * method - POST
 * params - vehicle_type_id (mandatory),lat (mandatory),long (mandatory) , booking_address(mandatory) , cargo_type_id (mandatory),price(mandatory),total_miles(mandatory) , cargo_type_notes ,  delivery_type_id (mandatory) , delivery_type_notes , pickup_date   
 * header Params - email (mandatory), password (mandatory)
 */
$app->post('/book_driver', 'authenticate', function () use ($app) {
    global $user_id;
    global $lang_code;
    verifyRequiredParams(array('vehicle_type_id', 'lat', 'long', 'booking_address', 'cargo_type_id', 'delivery_type_id','price','total_miles'));
   
    $params = array();
    $params['vehicle_type_id'] = $app->request()->post('vehicle_type_id');
    $params['lat'] = $app->request()->post('lat');
    $params['long'] = $app->request()->post('long');
    $params['user_id'] = $user_id;
    $params['pickup_date'] = $app->request()->post('pickup_date');
    $params['booking_address'] = $app->request()->post('booking_address');
    $params['cargo_type_id'] = $app->request()->post('cargo_type_id');
    $params['cargo_type_notes'] = $app->request()->post('cargo_type_notes');
    $params['delivery_type_id'] = $app->request()->post('delivery_type_id');
    $params['delivery_type_notes'] = $app->request()->post('delivery_type_notes');
    $params['price'] = $app->request()->post('price',0);
    $params['total_miles'] = $app->request()->post('total_miles',0);
    $params['dimension'] = $app->request()->post('dimension',0);
    $params['cubicfeet'] = $app->request()->post('cubicfeet',0);
    $params['lbh_dimension'] = $app->request()->post('lbh_dimension');

  //  pr($params);
    $response = array();

    $db = new DbHandler();
    $user = $db->bookDriver($params);
    if ($user == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($user == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "NO_NEARBY_DRIVER");
	$response['data'] = array();
    } else {
	$response['code'] = 0;
	$response['error'] = false;
	$response['message'] = getMessage($lang_code, "BOOKING_SUCCESS");
	$response['data'] = $user;
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * Get Driver pending booking request
 * url - /get_booking_request
 * method - GET
 * header Params - email (mandatory), password (mandatory)
 */
$app->get('/get_booking_request', 'authenticate', function () use ($app) {
    global $user_id;
	global $lang_code;
	
    $params = array();
    $params['driver_id'] = $user_id;

    $response = array();

    $db = new DbHandler();
    $res = $db->getBookingRequest($params);
    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($res == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response['data'] = array();
    } else {
	$response['code'] = 0;
	$response['error'] = false;
	$response['message'] = getMessage($lang_code, "SUCCESSFULLY_DONE");
	$response['data'] = $res;
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * Accept booking Request
 * url - /accept_booking_request
 * method - PUT
 * params - booking_id (mandatory)
 * header Params - email (mandatory), password (mandatory)
 */
$app->put('/accept_booking_request', 'authenticate', function () use ($app) {

    global $user_id;
	global $lang_code;
    verifyRequiredParams(array('booking_id'));
    $app = \Slim\Slim::getInstance();
    parse_str($app->request()->getBody(), $request_params);
    $params = array();
    $params['booking_id'] = $request_params['booking_id'];
    $params['driver_id'] = $user_id;

    $response = array();
    $db = new DbHandler();
    $res = $db->acceptBookingRequest($params);
    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($res == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response['data'] = array();
    } else {
	$response['code'] = 0;
	$response['error'] = false;
	$response['message'] = getMessage($lang_code, "SUCCESSFULLY_DONE");
	$response['data'] = array();
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * Reject Booking Request
 * url - /reject_booking_request
 * method - PUT
 * params - booking_id (mandatory) , reject_reason
 * header Params - email (mandatory), password (mandatory)
 */
$app->put('/reject_booking_request', 'authenticate', function () use ($app) {

    global $user_id;
	global $lang_code;
    verifyRequiredParams(array('booking_id'));
    $app = \Slim\Slim::getInstance();
    parse_str($app->request()->getBody(), $request_params);
    $params = array();
    $params['reject_reason'] = (!empty($request_params['reject_reason']) ? $request_params['reject_reason'] : '');
    ;
    $params['booking_id'] = $request_params['booking_id'];
    $params['driver_id'] = $user_id;

    $response = array();
    $db = new DbHandler();
    $res = $db->rejectBookingRequest($params);
    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($res == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response['data'] = array();
    } else {
	$response['code'] = 0;
	$response['error'] = false;
	$response['message'] = getMessage($lang_code, "SUCCESSFULLY_DONE");
	$response['data'] = array();
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});


/**
 * Cron job 
 * url - /cronjob
 * method - GET
 */
$app->get('/run_cron_job', function () use ($app) {
    $response = array();
    $db = new DbHandler();
    $res = $db->runCronJob();
    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = UNABLE_TO_PROCEED_MSG;
	$response['data'] = array();
    } else {
	$response['code'] = 0;
	$response['error'] = false;
	$response['message'] = SUCCESSFULLY_DONE_MSG;
	$response['data'] = array();
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * Cron job daily
 * url - /run_cron_job_daily
 * method - GET
 */
$app->get('/run_cron_job_daily', function () use ($app) {
    $response = array();
    $db = new DbHandler();
    $res = $db->runCronJobDaily();
    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = UNABLE_TO_PROCEED_MSG;
	$response['data'] = array();
    } else {
	$response['code'] = 0;
	$response['error'] = false;
	$response['message'] = SUCCESSFULLY_DONE_MSG;
	$response['data'] = array();
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * Get feedback Types
 * url - /get_feedback_types/:lang
 * method - GET
 * params - lang (mandatory) -> en/es,
 * header Params - email (mandatory), password (mandatory)
 */
$app->get('/get_feedback_types/:lang/', 'authenticate', function($lang) use ($app) {

    global $user_id;
    $db = new DbHandler();
    $res = $db->getFeedbackTypes($lang);

    if ($res == NO_RECORD_FOUND) {
	$response["code"] = NO_RECORD_FOUND;
	$response["error"] = true;
	$response["message"] = getMessage($lang, "NO_RECORD_FOUND");
	$response["data"] = array();
    } elseif ($res == UNABLE_TO_PROCEED) {
	$response["code"] = UNABLE_TO_PROCEED;
	$response["error"] = true;
	$response["message"] = getMessage($lang, "UNABLE_TO_PROCEED");
	$response["data"] = array();
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang, "SUCCESSFULLY_DONE");
	$response["data"] = $res;
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});


/**
 * Save feedback message
 * url - /feedback_request
 * method - POST
 * params - feedback_type_id (mandatory),message (mandatory) 
 * header Params - email (mandatory), password (mandatory)
 */
$app->post('/feedback_request', 'authenticate', function () use ($app) {
    global $user_id;
	global $lang_code;
    verifyRequiredParams(array('feedback_type_id', 'message'));
    $params = array();
    $params['feedback_type_id'] = $app->request()->post('feedback_type_id');
    $params['message'] = $app->request()->post('message');
    $params['user_id'] = $user_id;

    $response = array();

    $db = new DbHandler();
    $user = $db->feedbackRequest($params);
    if ($user == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($user == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response['data'] = array();
    } else if ($user == SUCCESSFULLY_DONE) {
	$response['code'] = 0;
	$response['error'] = false;
	$response['message'] = getMessage($lang_code, "FEEDBACK_DONE");
	$response['data'] = array();
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * User Profile Update
 * url - /updateprofile
 * method - POST
 * params - first_name(mandatory), last_name(mandatory), email(optional), phone(optional) , photo (optional)
 * header Params - username(mandatory), password(mandatory) 
 *
 */
$app->post('/updateprofile', 'authenticate', function() use ($app) {
    global $user_id;
	global $lang_code;
    $db = new DbHandler();
    // check for required params
    verifyRequiredParams(array('first_name', 'last_name'));

    $response = array('data'=>array());
    // reading post params
    $firstName = $app->request->post('first_name');
    $lastName = $app->request->post('last_name');
    $email = $app->request->post('email');
    $phone = $app->request->post('phone');

    if (isset($_FILES['photo']) && $_FILES['photo'] != '') {
	$image = $_FILES['photo'];
    } else {
	$image = '';
    }

    $userData = array('id' => $user_id, 'first_name' => $firstName, 'last_name' => $lastName, 'email' => $email, 'phone' => $phone, 'photo' => $image);

    $res = $db->updateUser($userData);
    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = 1;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
    } else if ($res == INVALID_USER) {
	$response['code'] = 2;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "INVALID_USER");
    } else if ($res == EMAIL_ALREADY_EXISTED) {
	$response['code'] = 3;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "EMAIL_ALREADY_EXISTED");
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang_code, "PROFILE_UPDATED_SUCCESSFULLY");

	$response['data']['user_id'] = $res['id'];
	$response['data']['first_name'] = $res['first_name'];
	$response['data']['last_name'] = $res['last_name'];
	$response['data']['email'] = $res['email'];
	$response['data']['phone'] = $res['phone'];
	$response['data']['customer_status'] = $res['customer_status'];
	$response['data']['driver_status'] = $res['driver_status'];
	$response['data']['login_from'] = $res['login_from'];
	$response['data']['user_type'] = $res['user_type'];
	$response['data']['last_login'] = $res['last_login'];
	$response['data']['photo_thumb'] = $response['data']['photo_large'] = '';

	/* 		if('D' == $data['user_type']){

	  $response['data']['ssn'] = $response['data']['driving_license_no'] = $response['data']['is_online'] = $response['data']['is_on_duty'] = '';
	  if(isset($res['DriverDetail']) && !empty($res['DriverDetail'])){
	  $response['data']['ssn'] = $res['DriverDetail']['ssn'];
	  $response['data']['driving_license_no'] = $res['DriverDetail']['driving_license_no'];
	  $response['data']['is_online'] = $res['DriverDetail']['is_online'];
	  $response['data']['is_on_duty'] = $res['DriverDetail']['is_on_duty'];
	  }

	  } */

	if ($res['photo'] != '') {
	    if (file_exists(USER_PHOTO_PATH_THUMB . $res['photo'])) {
		$response['data']['photo_thumb'] = USER_PHOTO_URL_THUMB . $res['photo'];
	    }
	    if (file_exists(USER_PHOTO_PATH_LARGE . $res['photo'])) {
		$response['data']['photo_large'] = USER_PHOTO_URL_LARGE . $res['photo'];
	    }
	}
    }
    echoRespnse(200, $response);
});


/**
 * Get User details
 * url - /get_user
 * method - GET
 * params - 
 * header Params - email (mandatory), password (mandatory)
 */
$app->get('/get_user', 'authenticate', function() use ($app) {

    global $user_id;
	global $lang_code;
    $params = array();
    $params['user_id'] = $user_id;
	
    $db = new DbHandler();
    $res = $db->getUser($params);
	
    if ($res == NO_RECORD_FOUND) {
	$response["code"] = NO_RECORD_FOUND;
	$response["error"] = true;
	
	$response["message"] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response["data"] = array();
    } elseif ($res == UNABLE_TO_PROCEED) {
	$response["code"] = UNABLE_TO_PROCEED;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response["data"] = array();
    } else {

	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang_code, "SUCCESSFULLY_DONE");
	$response["data"] = $res;
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * Get driver details
 * url - /get_driver_details
 * method - GET
 * params - 
 * header Params - email (mandatory), password (mandatory)
 */
$app->get('/get_driver_details/:lang/', 'authenticate', function($lang) use ($app) {

    global $user_id;
    $params = array();
    $params['user_id'] = $user_id;
    $params['lang_code'] = $lang;

    $db = new DbHandler();
    $res = $db->getDriverDetails($params);

    if ($res == NO_RECORD_FOUND) {
	$response["code"] = NO_RECORD_FOUND;
	$response["error"] = true;
	$response["message"] = getMessage($lang, "NO_RECORD_FOUND");
	$response["data"] = array();
    } elseif ($res == UNABLE_TO_PROCEED) {
	$response["code"] = UNABLE_TO_PROCEED;
	$response["error"] = true;
	$response["message"] = getMessage($lang, "UNABLE_TO_PROCEED");
	$response["data"] = array();
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang, "SUCCESSFULLY_DONE");
	$response["data"] = $res;
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});



/**
 * Driver Update profile
 * url - /update_driver_profile
 * method - POST
 * params -  first_name(mandatory), last_name(mandatory), email(optional), phone(optional) , photo (optional) , address(optional), country_id(optional),state_id(optional) ,city_id((optional),zip_code(optional)
 * header Params - username(mandatory), password(mandatory)  
 */
$app->post('/update_driver_profile', 'authenticate', function() use ($app) {
    global $user_id;
	global $lang_code;
    $db = new DbHandler();
    // check for required params
    verifyRequiredParams(array('first_name', 'last_name'));

    $response = array('data'=>array());
    // reading post params
    $firstName = $app->request->post('first_name');
    $lastName = $app->request->post('last_name');
    $email = $app->request->post('email');
    $phone = $app->request->post('phone');
    $address = $app->request->post('address');
    $countryId = $app->request->post('country_id');
    $stateId = $app->request->post('state_id');
    $cityId = $app->request->post('city_id');
    $zipCode = $app->request->post('zip_code');
    $dob = $app->request->post('dob');

    if (isset($_FILES['photo']) && $_FILES['photo'] != '') {
	$image = $_FILES['photo'];
    } else {
	$image = '';
    }

    $userData = array('driver_id' => $user_id,
	'first_name' => $firstName,
	'last_name' => $lastName,
	'email' => $email,
	'phone' => $phone,
	'photo' => $image,
	'address' => $address,
	'country_id' => $countryId,
	'state_id' => $stateId,
	'city_id' => $cityId,
	'zip_code' => $zipCode,
	'dob' => $dob,
	'image' => $image
    );

    $res = $db->updateDriver($userData);
    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = 1;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
    } else if ($res == INVALID_USER) {
	$response['code'] = 2;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "INVALID_USER");
    } else if ($res == EMAIL_ALREADY_EXISTED) {
	$response['code'] = 3;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "EMAIL_ALREADY_EXISTED");
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang_code, "PROFILE_UPDATED_SUCCESSFULLY");

	$response['data']['driver_id'] = $res['id'];
	$response['data']['first_name'] = $res['first_name'];
	$response['data']['last_name'] = $res['last_name'];
	$response['data']['email'] = $res['email'];
	$response['data']['phone'] = $res['phone'];
	$response['data']['address'] = $res['address'];
	$response['data']['country_id'] = $res['country_id'];
	$response['data']['state_id'] = $res['state_id'];
	$response['data']['city_id'] = $res['city_id'];
	$response['data']['zip_code'] = $res['zip_code'];
	$response['data']['dob'] = $res['dob'];
	$response['data']['photo_thumb'] = $response['data']['photo_large'] = '';

	if ($res['photo'] != '') {
	    if (file_exists(USER_PHOTO_PATH_THUMB . $res['photo'])) {
		$response['data']['photo_thumb'] = USER_PHOTO_URL_THUMB . $res['photo'];
	    }
	    if (file_exists(USER_PHOTO_PATH_LARGE . $res['photo'])) {
		$response['data']['photo_large'] = USER_PHOTO_URL_LARGE . $res['photo'];
	    }
	}
    }
    echoRespnse(200, $response);
});

/**
 * Add driver vehicles
 * url - /add_driver_vehicles
 * method - POST
 * params -  make_id(mandatory), model_id(mandatory), make_year(mandatory), color(mandatory) , plate_no (mandatory)
 * header Params - username(mandatory), password(mandatory)  
 */
$app->post('/add_driver_vehicles', 'authenticate', function() use ($app) {
    global $user_id;
	global $lang_code;
    $db = new DbHandler();
    // check for required params
    verifyRequiredParams(array('vehicle_type_id', 'make_id', 'model_id', 'make_year', 'color', 'plate_no'));

    $response = array('data'=>array());
    // reading post params
    $vehicleTypeId = $app->request->post('vehicle_type_id');
    $makeId = $app->request->post('make_id');
    $modelId = $app->request->post('model_id');
    $makeYear = $app->request->post('make_year');
    $color = $app->request->post('color');
    $plateNo = $app->request->post('plate_no');
   
    $userData = array('driver_id' => $user_id,
	'vehicle_type_id' => $vehicleTypeId,
	'make_id' => $makeId,
	'model_id' => $modelId,
	'make_year' => $makeYear,
	'color' => $color,
	'plate_no' => $plateNo,
    );
    
    if (isset($_FILES['insurance_policy_doc']) && $_FILES['insurance_policy_doc'] != '') {
	$userData['insurance_policy_doc'] = $_FILES['insurance_policy_doc'];
    }
    
    if (isset($_FILES['registration_doc']) && $_FILES['registration_doc'] != '') {
	$userData['registration_doc'] = $_FILES['registration_doc'];
    }

    $res = $db->addDriverVehicles($userData);
    
 

    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = 1;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
    } else if ($res == INVALID_USER) {
	$response['code'] = 2;
	$response['error'] = true;
	$response['message'] = "Invalid user";
    } else if ($res == 'INSURANCE_POLICY_DOC_SIZE') {
	$response["code"] = INSURANCE_POLICY_DOC_SIZE;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "INSURANCE_POLICY_DOC_SIZE");
//	echoRespnse(200, $response);
    } else if ($res == 'INSURANCE_POLICY_DOC_TYPE') {
	$response["code"] = INSURANCE_POLICY_DOC_TYPE;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "INSURANCE_POLICY_DOC_TYPE");
//	echoRespnse(200, $response);
    } else if ($res == 'REGISTRATION_DOC_SIZE') {
	$response["code"] = REGISTRATION_DOC_SIZE;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "REGISTRATION_DOC_SIZE");
//	echoRespnse(200, $response);
    } else if ($res == 'REGISTRATION_DOC_TYPE') {
	$response["code"] = REGISTRATION_DOC_TYPE;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "REGISTRATION_DOC_TYPE");
//	echoRespnse(200, $response);
    }else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang_code, "DRIVER_VEHICLE_ADDED");
    }
    echoRespnse(200, $response);
});

/**
 * Delete driver vehicle
 * url - /delete_driver_vehicle
 * method - DELETE
 * params - $id id primary key of vehicles table
 * header Params - email (mandatory), password (mandatory)
 */
$app->delete('/delete_driver_vehicle/:id/', 'authenticate', function($id) use ($app) {

    global $user_id;
	global $lang_code;
    $params = array();
    $params['id'] = $id;
    $params['driver_id'] = $user_id;

    $db = new DbHandler();
    $res = $db->deleteDriverVehicle($params);

    if ($res == NO_RECORD_FOUND) {
	$response["code"] = NO_RECORD_FOUND;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response["data"] = array();
    } elseif ($res == UNABLE_TO_PROCEED) {
	$response["code"] = UNABLE_TO_PROCEED;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response["data"] = array();
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang_code, "SUCCESSFULLY_DONE");
	$response["data"] = array();
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});


/**
 * Driver status
 * url - /driver_status
 * method - PUT
 * params - vehicle_id (mandatory)
 * header Params - email (mandatory), password (mandatory)
 */
$app->put('/driver_vehicle_online', 'authenticate', function () use ($app) {

    global $user_id;
	global $lang_code;
    verifyRequiredParams(array('vehicle_id'));
    $app = \Slim\Slim::getInstance();
    parse_str($app->request()->getBody(), $request_params);
    $params = array();
    $params['driver_id'] = $user_id;
    $params['vehicle_id'] = (isset($request_params['vehicle_id']) ? $request_params['vehicle_id'] : 0);

    $response = array();
    $db = new DbHandler();
    $res = $db->driverVehicleOnline($params);
    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($res == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response['data'] = array();
    } else {
	$response['code'] = 0;
	$response['error'] = false;
	$response['message'] = getMessage($lang_code, "SUCCESSFULLY_DONE");
	$response['data'] = array();
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * Driver vehicle offline
 * url - /driver_vehicle_offline
 * method - PUT
 * header Params - email (mandatory), password (mandatory)
 */
$app->put('/driver_vehicle_offline', 'authenticate', function () use ($app) {

    global $user_id;
    global $lang_code;
    $app = \Slim\Slim::getInstance();
    parse_str($app->request()->getBody(), $request_params);
    $params = array();
    $params['driver_id'] = $user_id;
    
    $response = array();
    $db = new DbHandler();
    $res = $db->driverVehicleOffline($params);
    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($res == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response['data'] = array();
    } else {
	$response['code'] = 0;
	$response['error'] = false;
	$response['message'] = getMessage($lang_code, "SUCCESSFULLY_DONE");
	$response['data'] = array();
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * Get driver completed bookings
 * url - /get_user_completed_booking
 * method - GET
 * params - 
 * header Params - email (mandatory), password (mandatory)
 */
$app->get('/get_user_completed_booking', 'authenticate', function() use ($app) {

    global $user_id;
	global $lang_code;
    $params = array();
    $params['user_id'] = $user_id;

    $db = new DbHandler();
    $res = $db->getUserCompletedBooking($params);

    if ($res == NO_RECORD_FOUND) {
	$response["code"] = NO_RECORD_FOUND;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response["data"] = array();
    } elseif ($res == UNABLE_TO_PROCEED) {
	$response["code"] = UNABLE_TO_PROCEED;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response["data"] = array();
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang_code, "SUCCESSFULLY_DONE");
	$response["data"] = $res;
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * Get driver upcoming bookings
 * url - /get_user_upcoming_booking
 * method - GET
 * params - 
 * header Params - email (mandatory), password (mandatory)
 */
$app->get('/get_user_upcoming_booking', 'authenticate', function() use ($app) {

    global $user_id;
	global $lang_code;
    $params = array();
    $params['user_id'] = $user_id;

    $db = new DbHandler();
    $res = $db->getUserUpcomingBooking($params);

    if ($res == NO_RECORD_FOUND) {
	$response["code"] = NO_RECORD_FOUND;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response["data"] = array();
    } elseif ($res == UNABLE_TO_PROCEED) {
	$response["code"] = UNABLE_TO_PROCEED;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response["data"] = array();
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	//$response["message"] = "Upcoming bookings";
	$response["message"] = getMessage($lang_code, "UPCOMING_BOOKING");
	
	$response["data"] = $res;
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * Get driver earnings
 * url - /get_driver_earnings
 * method - GET
 * params - 
 * header Params - email (mandatory), password (mandatory)
 */
$app->get('/get_driver_earnings', 'authenticate', function() use ($app) {

    global $user_id;
	global $lang_code;
    $params = array();
    $params['driver_id'] = $user_id;

    $db = new DbHandler();
    $res = $db->getDriverEarnings($params);

    if ($res == NO_RECORD_FOUND) {
	$response["code"] = NO_RECORD_FOUND;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "NO_RECORD_FOUND");
    } elseif ($res == UNABLE_TO_PROCEED) {
	$response["code"] = UNABLE_TO_PROCEED;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "UNABLE_TO_PROCEED");
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang_code, "SUCCESSFULLY_DONE");
	$response["data"] = $res;
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});


/**
 * Get driver payment history
 * url - /get_driver_payment_history
 * method - GET
 * params - 
 * header Params - email (mandatory), password (mandatory)
 */
$app->get('/get_driver_payment_history', 'authenticate', function() use ($app) {

    global $user_id;
	global $lang_code;
    $params = array();
    $params['driver_id'] = $user_id;

    $db = new DbHandler();
    $res = $db->getDriverPaymentHistory($params);  

    if ($res == NO_RECORD_FOUND) {
	$response["code"] = NO_RECORD_FOUND;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response['data'] = array();
    } elseif ($res == UNABLE_TO_PROCEED) {
	$response["code"] = UNABLE_TO_PROCEED;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang_code, "SUCCESSFULLY_DONE");
	$response["data"] = $res;
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});


/**
 * Get driver payment history details
 * url - /get_driver_payment_history_details/:transactionIds
 * method - GET
 * params - 
 * header Params - email (mandatory), password (mandatory)
 */
$app->get('/get_driver_payment_history_details/:transactionIds', 'authenticate', function($transactionIds) use ($app) {

    global $user_id;
	global $lang_code;
    $params = array();
    $params['transaction_ids'] = $transactionIds;

    $db = new DbHandler();
    $res = $db->getDriverPaymentHistoryDetails($params);

    if ($res == NO_RECORD_FOUND) {
	$response["code"] = NO_RECORD_FOUND;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response['data'] = array();
    } elseif ($res == UNABLE_TO_PROCEED) {
	$response["code"] = UNABLE_TO_PROCEED;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang_code, "SUCCESSFULLY_DONE");
	$response["data"] = $res;
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});



/**
 * switch mode
 * url - /switch_mode
 * method - POST
 * params -  
 * header Params - username(mandatory), password(mandatory)  
 */
$app->post('/switch_mode', 'authenticate', function() use ($app) {
    global $user_id;
	global $lang_code;
    $db = new DbHandler();

    $response = array();
    $res = $db->switchMode(array('user_id'=>$user_id));

    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = 1;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($res == INVALID_USER) {
	$response['code'] = 2;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "INVALID_USER");
	$response['data'] = array();
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang_code, "USER_TYPE_CHANGED");
	
	$response['data'] = $res;
    }
    echoRespnse(200, $response);
});

/**
 * Driver Arrived
 * url - /driver_arrived
 * method - POST
 * params -  arrived (mandatory) string value 'S','D'  
 * params -  booking_location_id (mandatory) int
 * header Params - username(mandatory), password(mandatory)  
 */
$app->post('/driver_arrived', 'authenticate', function() use ($app) {
    global $user_id;
	global $lang_code;
    $db = new DbHandler();
    
    // check for required params
    verifyRequiredParams(array('arrived','booking_location_id'));

    $response = array();
    // reading post params
    $arrived = $app->request->post('arrived');
    $arrived = strtoupper(trim($arrived));
    $bookingLocationId = $app->request->post('booking_location_id');
    
    $bookingLocationData = array('driver_id' => $user_id,
	'arrived' => $arrived,
	'booking_location_id'=> $bookingLocationId,
    );

    $response = array();
    $res = $db->driverArrived($bookingLocationData);

    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = 1;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($res == INVALID_USER) {
	$response['code'] = 2;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "INVALID_USER");
	$response['data'] = array();
    } else if ($res == INVALID_ARRIVE_TYPE) {
	$response['code'] = INVALID_ARRIVE_TYPE;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "INVALID_ARRIVE_TYPE");
	$response['data'] = array();
    } else if($res == NO_RECORD_FOUND) {
	$response["code"] = NO_RECORD_FOUND;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response["data"] = array();
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang_code, "DRIVER_ARRIVED");
	$response['data'] = array();
    }
    echoRespnse(200, $response);
});


/**
 * Driver Navigate
 * url - /driver_navigate
 * method - POST
 * params -  arrived (mandatory) string value 'S','D'  
 * params -  booking_location_id (mandatory) int
 * header Params - username(mandatory), password(mandatory)  
 */
$app->post('/driver_navigate', 'authenticate', function() use ($app) {
    global $user_id;
	global $lang_code;
    $db = new DbHandler();
    
    // check for required params
    verifyRequiredParams(array('arrived','booking_location_id'));

    $response = array();
    // reading post params
    $arrived = $app->request->post('arrived');
    $arrived = strtoupper(trim($arrived));
    $bookingLocationId = $app->request->post('booking_location_id');
    
    $bookingLocationData = array('driver_id' => $user_id,
	'arrived' => $arrived,
	'booking_location_id'=> $bookingLocationId,
    );

    $response = array();
    $res = $db->driverNavigate($bookingLocationData);

    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = 1;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($res == INVALID_USER) {
	$response['code'] = 2;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "INVALID_USER");
	$response['data'] = array();
    } else if ($res == INVALID_ARRIVE_TYPE) {
	$response['code'] = INVALID_ARRIVE_TYPE;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "INVALID_ARRIVE_TYPE");
	$response['data'] = array();
    } else if($res == NO_RECORD_FOUND) {
	$response["code"] = NO_RECORD_FOUND;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response["data"] = array();
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang_code, "DRIVER_ARRIVED");
	$response['data'] = array();
    }
    echoRespnse(200, $response);
});

/**
 * calculate_address
 * url - /calculate_address
 * method - POST
 * params - booking_address (mandatory),
 */
$app->post('/calculate_address', 'authenticate', function () use ($app) {
    global $user_id;
	global $lang_code;
    verifyRequiredParams(array('booking_address'));

    $params = array();
    $params['booking_address'] = $app->request()->post('booking_address');
    
    $response = array();

    $db = new DbHandler();
    $result = $db->calculateAddress($params);
    if ($result == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($result == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = '';
	$response['data'] = array();
    } else {
	$response['code'] = 0;
	$response['error'] = false;
	$response['message'] = getMessage($lang_code, "DISTANCE_LAT_LONG");
	$response['data'] = $result;
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});


/**
 * User addcreditcard
 * url - /addcreditcard
 * method - POST
 * params -  'cardholdername', 'cvv', 'expirationDate', 'number'
 * header Params - email (mandatory), password (mandatory)
 */
$app->post('/addcreditcard', 'authenticate', function () use ($app) {
    global $user_id;
    verifyRequiredParams(array('cardholdername', 'cvv', 'expirationDate', 'number', 'is_default'));

    $cardholdername = $app->request()->post('cardholdername');
    $cvv = $app->request()->post('cvv');
    $expirationDate = $app->request()->post('expirationDate');
    $number = $app->request()->post('number');
    $is_default = $app->request()->post('is_default');

    $response = array();

    $db = new DbHandler();
    $user = $db->addcreditcard($user_id, $cardholdername, $cvv, $expirationDate, $number, $is_default);
    if (!$user->success) {
        $response['code'] = 1;
        $response['error'] = true;
        $response['message'] = $user->message;
    } else {
        $response['code'] = 0;
        $response['error'] = false;
        $response['message'] = "Card successfully added";
        $response['data'] = array('token'=>$user->creditCard->token, 'uniqueNumberIdentifier'=>$user->creditCard->uniqueNumberIdentifier);
    }
    echoRespnse(200, $response);
});

/**
 * Genrate Token
 * url - /generatetoken
 * method - GET  
 */
$app->get('/generatetoken','authenticate', function() use ($app){

    global $user_id;
    //$user_id = 2;
	global $lang_code;
    $response = array('data'=>array());

    $db = new DbHandler();

    $getToken = $db->GenerateToken($user_id);

    if ($getToken == UNABLE_TO_PROCEED) {
	$response['code'] = 1;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "NO_RECORD_FOUND");
    } else {
	$response['code'] = 0;
	$response['error'] = false;
	$response['message'] = "Token Detail:";
	$response['data'] = array('token' => $getToken);
    }
    
    echoRespnse(200, $response);
});

/**
 * Booking complete
 * url - /booking_complete
 * method - POST
 * params -  booking_id (mandatory) int
 * header Params - username(mandatory), password(mandatory)  
 */
// $app->post('/booking_complete', function() use ($app) {
$app->post('/booking_complete', 'authenticate', function() use ($app) {
    global $user_id;
    // $user_id = 8;
	global $lang_code;
	$lang_code = 'en';
    $db = new DbHandler();
    
    // check for required params
    verifyRequiredParams(array('booking_id'));
    

    $response = array();
    // reading post params
    $bookingId 		= $app->request()->post('booking_id');
	$travel_route   = $app->request()->post('travel_route');
	

    $bookingData = array('booking_id' => $bookingId,'travel_route' => $travel_route);

    $response = array();
    //$res = $db->bookingComplete($bookingData);
	$res = $db->bookingCompleted($bookingData);//new function added by Abhishek

     if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($res == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response['data'] = array();
    } else if ($res == BOOKING_CANCEL_ERROR) {
	$response['code'] = BOOKING_CANCEL_ERROR;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "BOOKING_CANCEL_ERROR");
	$response['data'] = array();
    } else if ($res == BOOKING_COMPLETED_ARRIVED_ERROR) {
	$response['code'] = BOOKING_COMPLETED_ARRIVED_ERROR;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "BOOKING_COMPLETED_ARRIVED_ERROR");
	$response['data'] = array();
    } else if ($res == INVALID_DRIVER_BANK_ACCOUNT) {
	$response['code'] = INVALID_DRIVER_BANK_ACCOUNT;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "INVALID_DRIVER_BANK_ACCOUNT");
	$response['data'] = array();
    }else {
		if(isset($res['msg']) && !empty($res['msg'])){
			$errorMsg = $res['msg'];
			$response['code'] = INVALID_BANK_ACCOUNT;
			$response['error'] = false;
			$response['message'] = $errorMsg;
			$response['data'] = array();
		}
		else{
			$response["code"] = 0;
			$response["error"] = false;
			$response["message"] = getMessage($lang_code, "BOOKING_COMPLETE");
			$response['data'] = array();
		}	
    }
    echoRespnse(200, $response);
});

/**
 * Driver Arrived
 * url - /booking_rating
 * method - POST
 * params -  rating (mandatory) 
 * params -  booking_id (mandatory) int
 * params -  comment (optional) varchar
 * header Params - username(mandatory), password(mandatory)  
 */
$app->post('/booking_rating', 'authenticate', function() use ($app) {
    global $user_id;
	global $lang_code;
    $db = new DbHandler();
    
    // check for required params
    verifyRequiredParams(array('rating','booking_id'));

    $response = array();
    // reading post params
    $rating = $app->request->post('rating');
    $comment = $app->request->post('comment');
    $bookingId = $app->request->post('booking_id');
    
    $ratingData = array(
	'user_id' => $user_id,
	'rating' => $rating,
	'comment' => $comment,
	'booking_id' => $bookingId,
    );

    $response = array();
    $res = $db->bookingRating($ratingData);

    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($res == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response['data'] = array();
    } else if ($res == USER_ALREADY_RATE) {
	$response['code'] = USER_ALREADY_RATE;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "USER_ALREADY_RATE");
	$response['data'] = array();
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	//$response["message"] = "Booking has been rated";
	$response["message"] = getMessage($lang_code, "BOOKING_RATED");
	$response['data'] = array();
    }
    echoRespnse(200, $response);
});

/**
 * Get driver payment history
 * url - /get_driver_payment_history
 * method - GET
 * params - 
 * header Params - email (mandatory), password (mandatory)
 */
$app->get('/get_credit_card', 'authenticate', function() use ($app) {

    global $user_id;
	global $lang_code;
    $params = array();
    $params['user_id'] = $user_id;

    $db = new DbHandler();
    $res = $db->getCreditCard($params);

    if ($res == NO_RECORD_FOUND) {
	$response["code"] = NO_RECORD_FOUND;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response["data"] = array();
    } elseif ($res == UNABLE_TO_PROCEED) {
	$response["code"] = UNABLE_TO_PROCEED;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response["data"] = array();
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang_code, "CREDIT_CARD_DETAILS");
	$response["data"] = $res;
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * Set Credit Card
 * url - /set_credit_card
 * method - PUT
 * params - credit_card_id (mandatory)
 * header Params - email (mandatory), password (mandatory)
 */
$app->put('/set_credit_card', 'authenticate', function () use ($app) {

    global $user_id;
	global $lang_code;
    verifyRequiredParams(array('credit_card_id'));
    $app = \Slim\Slim::getInstance();
    parse_str($app->request()->getBody(), $request_params);
    $params = array();
    $params['credit_card_id'] = $request_params['credit_card_id'];
    $params['user_id'] = $user_id;

    $response = array();
    $db = new DbHandler();
    $res = $db->setCreditCard($params);
    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($res == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response['data'] = array();
    } else {
	$response['code'] = 0;
	$response['error'] = false;
	$response['message'] = getMessage($lang_code, "CREDIT_CARD_SET_DEFAULT");
	$response['data'] = array();
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * Booking cancel
 * url - /booking_cancel
 * method - POST
 * params -  booking_id (mandatory) int
 * header Params - username(mandatory), password(mandatory)  
 */
$app->post('/booking_cancel', 'authenticate', function() use ($app) {
    global $user_id;
    global $lang_code;
    $db = new DbHandler();
    
    // check for required params
    verifyRequiredParams(array('booking_id'));
    

    $response = array();
    // reading post params
    $bookingId = $app->request()->post('booking_id');
    
    $bookingData = array('booking_id' => $bookingId);

    $response = array();
    $res = $db->bookingCancel($bookingData);

    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($res == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response['data'] = array();
    }  else if ($res == BOOKING_CANCEL_ERROR) {
	$response['code'] = BOOKING_CANCEL_ERROR;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "BOOKING_CANCEL_ERROR");
	$response['data'] = array();
    }  else if ($res == BOOKING_CANCEL_TIME_ERROR) {
	$response['code'] = BOOKING_CANCEL_TIME_ERROR;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "BOOKING_CANCEL_TIME_ERROR");
	$response['data'] = array();
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang_code, "BOOKING_CANCELLED");
	$response['data'] = array();
    }
    echoRespnse(200, $response);
	//echoRespnse(200, $res);
});

/**
 * Rider Booking cancel
 * url - /rider_booking_cancel
 * method - POST
 * params -  booking_id (mandatory) int
 * header Params - username(mandatory), password(mandatory)  
 */
$app->post('/rider_booking_cancel', 'authenticate', function() use ($app) {
    global $user_id;
    global $lang_code;
    $db = new DbHandler();
    
    // check for required params
    verifyRequiredParams(array('booking_id'));

    $response = array();
    // reading post params
    $bookingId = $app->request()->post('booking_id');
    
    $bookingData = array('booking_id' => $bookingId);

    $response = array();
    $res = $db->riderBookingCancel($bookingData);
    


    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($res == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response['data'] = array();
    }  else if ($res == BOOKING_CANCEL_ERROR) {
	$response['code'] = BOOKING_CANCEL_ERROR;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "BOOKING_CANCEL_ERROR");
	$response['data'] = array();
    }  else if ($res == BOOKING_CANCEL_RIDER_TIME_ERROR) {
	$response['code'] = BOOKING_CANCEL_RIDER_TIME_ERROR;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "BOOKING_CANCEL_RIDER_TIME_ERROR");
	$response['data'] = array();
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang_code, "BOOKING_CANCELLED");
	$response['data'] = array();
    }
    echoRespnse(200, $response);
	//echoRespnse(200, $res);
});

/**
 * Logout
 * url - /logout
 * method - POST
 * params -  
 * header Params - username(mandatory), password(mandatory)  
 */
$app->post('/logout', 'authenticate', function() use ($app) {
    global $user_id;
	global $lang_code;
    $db = new DbHandler();

    $response = array();
    // reading post params
    $userData = array('user_id' => $user_id);

    $response = array();
    $res = $db->logout($userData);

    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($res == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response['data'] = array();
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang_code, "LOGOUT_SUCCESS");
	$response['data'] = array();
    }
    echoRespnse(200, $response);
});

/**
 * check device token
 * url - /check_device_token
 * method - POST
 * params - device_token(mandatory) 
 * header Params - username(mandatory), password(mandatory)  
 */
$app->post('/check_device_token', 'authenticate', function() use ($app) {
    global $user_id;
	global $lang_code;
    $db = new DbHandler();

    // check for required params
    verifyRequiredParams(array('device_token'));
    $response = array();
    // reading post params
    $deviceToken = $app->request()->post('device_token');
    $userData = array('user_id' => $user_id,'device_token'=>$deviceToken);

    $response = array();
    $res = $db->check_device_token($userData);

    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($res == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response['data'] = array();
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang_code, "SUCCESSFULLY_DONE");
	$response['data'] = $res;
    }
    echoRespnse(200, $response);
});

/**
 * Get Booking Details
 * url - /get_booking_details
 * method - GET
 * params - 
 * header Params - email (mandatory), password (mandatory)
 */
$app->get('/get_booking_details/:booking_id', 'authenticate', function($bookingId) use ($app) {

    global $user_id;
    global $lang_code;
    $params = array();
    $params['booking_id'] = $bookingId;
    $params['user_id'] = $user_id;
    $params['lang_code'] = $lang_code;

    $db = new DbHandler();
    $res = $db->getBookingDetails($params);

    if ($res == NO_RECORD_FOUND) {
	$response["code"] = NO_RECORD_FOUND;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response["data"] = array();
    } elseif ($res == UNABLE_TO_PROCEED) {
	$response["code"] = UNABLE_TO_PROCEED;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response["data"] = array();
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	$response["message"] = getMessage($lang_code, "BOOKING_DETAILS");
	$response["data"] = $res;
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * Get bank details
 * url - /get_bank_details
 * method - GET
 * params - 
 * header Params - email (mandatory), password (mandatory)
 */
$app->get('/get_bank_details', 'authenticate', function() use ($app) {

    global $user_id;
	global $lang_code;
    $params = array();
    $params['user_id'] = $user_id;

    $db = new DbHandler();
    $res = $db->getBankDetails($params);

    if ($res == NO_RECORD_FOUND) {
	$response["code"] = NO_RECORD_FOUND;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response["data"] = array();
    } elseif ($res == UNABLE_TO_PROCEED) {
	$response["code"] = UNABLE_TO_PROCEED;
	$response["error"] = true;
	$response["message"] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response["data"] = array();
    } else {
	$response["code"] = 0;
	$response["error"] = false;
	//$response["message"] = "Bank details";
	$response["message"] = getMessage($lang_code, "BANK_DETAILS");
	$response["data"] = $res;
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * Set Bank Details
 * url - /set_bank_details
 * method - POST
 * params - account_type (mandatory),router_no (mandatory), account_no (mandatory)
 * header Params - email (mandatory), password (mandatory)
 */
$app->post('/set_bank_details', 'authenticate', function () use ($app) {
    global $user_id;
	global $lang_code;
    $db = new DbHandler();
    // check for required params
    verifyRequiredParams(array('account_type','router_no','account_no'));
    $response = array();
    // reading post params
    $params = array();
    $params['account_type'] = $app->request()->post('account_type');
    $params['router_no'] = $app->request()->post('router_no');
    $params['account_no'] = $app->request()->post('account_no');
    $params['user_id'] = $user_id;
    
    $db = new DbHandler();
    $res = $db->setBankDetails($params);
    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($res == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response['data'] = array();
    } else if ($res == INVALID_ACCOUNT_TYPE) {
	$response['code'] = INVALID_ACCOUNT_TYPE;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "INVALID_ACCOUNT_TYPE");
	$response['data'] = array();
    } else if ($res == INVALID_BANK_ACCOUNT) {
	$response['code'] = INVALID_BANK_ACCOUNT;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "INVALID_BANK_ACCOUNT");
	$response['data'] = array();
    }  else if ($res == SUCCESSFULLY_DONE) {
		$response['code'] = SUCCESSFULLY_DONE;
		$response['error'] = true;
		$response['message'] = getMessage($lang_code, "BANK_DETAILS_SAVED");
		$response['data'] = array();
    } else {
		if(!empty($res['msg'])){
			$errorMsg = $res['msg'];
			$response['code'] = INVALID_BANK_ACCOUNT;
			$response['error'] = false;
			$response['message'] = $errorMsg;
			$response['data'] = array();
		}else{
			$response['code'] = UNABLE_TO_PROCEED;
			$response['error'] = true;
			$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
			$response['data'] = array();
		}
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * Set Bank Details
 * url - /set_bank_details
 * method - POST
 * params - account_type (mandatory),router_no (mandatory), account_no (mandatory)
 * header Params - email (mandatory), password (mandatory)
 */
$app->post('/pay_to_driver_bank_test', 'authenticate', function () use ($app) {
    global $user_id;
	global $lang_code;
    $db = new DbHandler();
	// check for required params
  //  verifyRequiredParams(array('account_type','router_no','account_no'));
    $response = array();
    // reading post params
    $params = array();
    $params['account_type'] = $app->request()->post('account_type');
    $params['router_no'] = $app->request()->post('router_no');
    $params['account_no'] = $app->request()->post('account_no');
    $params['user_id'] = $user_id;
    
    $db = new DbHandler();
    $res = $db->payToDriverBank($params);
    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } else if ($res == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response['data'] = array();
    } else if ($res == INVALID_ACCOUNT_TYPE) {
	$response['code'] = INVALID_ACCOUNT_TYPE;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "INVALID_ACCOUNT_TYPE");
	$response['data'] = array();
    } else {
	$response['code'] = 0;
	$response['error'] = false;
	//$response['message'] = 'Bank account details have been saved';
	$response['message'] = getMessage($lang_code, "BANK_DETAILS_SAVED");
	$response['data'] = array();
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

//------ 01-12-2016 CREATED BY MANOJ -------//
/**
 * Check if user has added any cc card with his account
 * url - /iscardadded
 * method - GET
 * params - 
 * header Params - email (mandatory), password (mandatory)
 */
$app->get('/iscardadded', 'authenticate', function() use ($app) {
    global $user_id;
	global $lang_code;
    $params = array();
    $db = new DbHandler();
    $res = $db->isCardAdded($user_id);
    if ($res == 'NO_RECORD_FOUND') {
		$response["code"] = NO_RECORD_FOUND;
		$response["error"] = true;
		$response["message"] = getMessage($lang_code, "NO_RECORD_FOUND");
    } else {
		$response["code"] = 0;
		$response["error"] = false;
		//$response["message"] = $res." Credit card added";
		$response["message"] = $res.getMessage($lang_code, "CREDIT_CARD_ADDED");
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * Report a Problem (Driver end)
 * url - /reportaproblem
 * method - POST
 * params - title(mandatory), description(mandatory)
 * header Params - email (mandatory), password (mandatory)
 */
$app->post('/reportaproblem', 'authenticate', function () use ($app) {
    global $user_id;
	global $lang_code;
    $db = new DbHandler();
    // check for required params
    verifyRequiredParams(array('title','description'));
    $response = array();
    // reading post params
    $params = array();
    $params['title'] = $app->request()->post('title');
    $params['description'] = $app->request()->post('description');
    $params['user_id'] = $user_id;
    
    $db = new DbHandler();
    $res = $db->reportaProblem($params);
    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	//$response['data'] = array();
    } else {
	$response['code'] = 0;
	$response['error'] = false;
	//$response['message'] = 'Successfully reported';
	$response['message'] = getMessage($lang_code, "REPORTED_SUCCESS");
	//$response['data'] = array();
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * Get Running Trip Details
 * url - /get_running_trip_details/:lang
 * params - lang (mandatory)
 * method - GET
  * header Params - email (mandatory), password (mandatory)
 */
$app->get('/get_running_trip_details/:lang', 'authenticate', function($lang) use ($app) {

    global $user_id;

    $db = new DbHandler();
    $r = $db->getCurrentTripDetails($user_id, $lang);
    if ($r == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = "Unable to proceed your request";
    } else if ($r == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = "No record found!";
    } else {
	$response['code'] = 0;
	$response['error'] = false;
	$response['message'] = "Trip details";
	$response['data'] = $r;
    }
    echoRespnse(200, $response);
});

/**
 * Get driver assign trip details
 * url - /get_driver_assign_trip_details/:lang
 * params - lang (mandatory)
 * method - GET
  * header Params - email (mandatory), password (mandatory)
 */
$app->get('/get_driver_assign_trip_details/:lang', 'authenticate', function($lang) use ($app) {

    global $user_id;

    $db = new DbHandler();
    $r = $db->getDriverAssignTripDetails($user_id, $lang);
    if ($r == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = "Unable to proceed your request";
    } else if ($r == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = "No record found!";
    } else {
	$response['code'] = 0;
	$response['error'] = false;
	$response['message'] = "Trip details";
	$response['data'] = $r;
    }
    echoRespnse(200, $response);
});


/**
 * Get driver status
 * url - /get_driver_status/:lang
 * method - GET
 * params - lang (mandatory) -> en/es,
 */
$app->get('/get_driver_status/:lang', 'authenticate' ,function($lang) use ($app) {

	global $user_id;

    $db = new DbHandler();
    $r = $db->get_driver_status($user_id);
    
	$response = array(
	'code' => 1,
	'error' => true,
	'message' => getMessage($lang, "NO_RECORD_FOUND"),
	'data' => array()
    );
	
	if ('' != $r && UNABLE_TO_PROCEED != $r) {

	$response = array(
	    'code' => 0,
	    'error' => false,
	    'message' => getMessage($lang, "SUCCESSFULLY_DONE"),
	    'data' => $r
	);
    }

    echoRespnse(200, $response);
});

/**
 * Refresh Token
 * url - /refresh_token
 * method - POST
 * params - device_token(mandatory),device_type(mandatory),
 * Note: device_type - A=Android, I=IOS,
 */
$app->post('/refresh_token', 'authenticate' ,function() use ($app) {

	global $user_id;
	$lang_code = DEFAULT_LANGUAGE;

    // check for required params
    verifyRequiredParams(array('device_type', 'device_token'));
    $response = array();

    // reading post params
    $data['device_type'] = $app->request->post('device_type');
    $data['device_token'] = $app->request->post('device_token');
    $data['user_id'] = $user_id;

    $db = new DbHandler();
    $res = $db->refresh_token($data);

    if ($res == UNABLE_TO_PROCEED) {
		$response['code'] = UNABLE_TO_PROCEED;
		$response['error'] = true;
		$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");		
    } else {
		$response['code'] = 0;
		$response['error'] = false;
		$response['message'] = getMessage($lang_code, "SUCCESSFULLY_DONE");
    }
    echoRespnse(200, $response);
});

/**
 * Send test email
 */
$app->get('/test_email', function() use ($app) {
	
    $response = array();
    $db = new DbHandler();

    $to = 'jaswant@mailinator.com';
    $subject = 'subject header msg';
    $message = '<h1>This is test body</h1>';
	$senderName = 'Jaswant';    
    $db->sendSMTPmail($to , $subject, $message,  $senderName="");
	echoRespnse(200, $response);
});


############### OLD API ###############

/**
 * generatetoken
 * url - /generatetoken
 * method - GET
 * params - customer_id (mandatory), 
 */
/* $app->get('/generatetoken', function() use ($app){
  $response = array();

  require_once 'Setup.php';

  Configuration::environment('sandbox');
  Configuration::merchantId('tbb23ppsvmcmxrmc');
  Configuration::publicKey('kc9k4sgn4wxxg666');
  Configuration::privateKey('41000f2f335b5f4d2df8da86f66a136a');
  $clientToken = Braintree_ClientToken::generate();

  if ($clientToken=='')
  {
  $response['code'] = 1;
  $response['error'] = true;
  $response['message'] = "Some Errors Occured, please try after some time";
  }
  else
  {
  $response['code'] = 0;
  $response['error'] = false;
  $response['message'] = "Token successfully generated.";
  $response['data'] = array('token'=>$clientToken);
  }

  echoRespnse(200, $response);
  }); */


/**
 * braintreetransaction
 * url - /braintreetransaction
 * method - GET
 */
/* $app->post('/braintreetransaction', function() use ($app){


  verifyRequiredParams(array('payment_method_nonce'));

  $payment_method_nonce = $app->request()->post('payment_method_nonce');

  require_once 'Setup.php';

  Configuration::environment('sandbox');
  Configuration::merchantId('tbb23ppsvmcmxrmc');
  Configuration::publicKey('kc9k4sgn4wxxg666');
  Configuration::privateKey('41000f2f335b5f4d2df8da86f66a136a');

  $result = Braintree_Transaction::sale([
  'amount' => '100.00',
  'paymentMethodNonce' => $payment_method_nonce,
  'options' => [
  'submitForSettlement' => True
  ]
  ]);

  if (!$result->success)
  {
  $response['code'] = 1;
  $response['error'] = true;
  $response['message'] = $result->errors->deepAll();
  }
  else
  {
  $transaction = $result->transaction;
  $transaction->status;
  $response['code'] = 0;
  $response['error'] = false;
  $response['message'] = "Payment successfully done.";
  $response['data'] = array('transaction_status'=>$transaction->status, 'transaction_id'=>$transaction->id);
  }

  echoRespnse(200, $response);
  }); */

############## NEW APIs BY ABHISHEK FOR STRIPE PAYMENT GATEWAY #############
/**
 * Bank Details
 * url - /add_bank_details
 * method - POST
 * params - dob (mandatory y-m-d (91-01-01)), country_id (mandatory), state_id (mandatory), city_id(mandatory), address (mandatory), zip_code (mandatory), account_type (mandatory), router_no (mandatory), account_no (mandatory)
 * header Params - email (mandatory), password (mandatory)
 * New API
 */
$app->post('/add_bank_details', 'authenticate', function () use ($app) {
    global $user_id;
	global $lang_code;
    $db = new DbHandler();
   
    // check for required params
    verifyRequiredParams(array('dob','country_id','state_id','city_id','address', 'zip_code', 'account_type','router_no','account_no'));
    $response = array();
    // reading post params
    $params = array();
    $params['account_type'] = $app->request()->post('account_type');
    $params['router_no'] = $app->request()->post('router_no');
    $params['account_no'] = $app->request()->post('account_no');
	
    $params['dob'] = $app->request()->post('dob');
    $params['country_id'] = $app->request()->post('country_id');
    $params['state_id'] = $app->request()->post('state_id');
    $params['city_id'] = $app->request()->post('city_id');
	$params['address'] = $app->request()->post('address');
	$params['zip_code'] = $app->request()->post('zip_code');

    $params['user_id'] = $user_id;
    
	//echo getMessage($lang_code, "DRIVER_ACCOUNT_DEACTVATED");
    $db = new DbHandler();
    $res = $db->addBankDetails($params, $lang_code);
   
    if ($res == UNABLE_TO_PROCEED) {
	$response['code'] = UNABLE_TO_PROCEED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
	$response['data'] = array();
    } 
    else if ($res == DRIVER_ACCOUNT_DEACTVATED) {
	$response['code'] = DRIVER_ACCOUNT_DEACTVATED;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "DRIVER_ACCOUNT_DEACTVATED");
	$response['data'] = array();
    } 
	else if ($res == NO_RECORD_FOUND) {
	$response['code'] = NO_RECORD_FOUND;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "NO_RECORD_FOUND");
	$response['data'] = array();
    } else if ($res == INVALID_ACCOUNT_TYPE) {
	$response['code'] = INVALID_ACCOUNT_TYPE;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "INVALID_ACCOUNT_TYPE");
	$response['data'] = array();
    } else if ($res == INVALID_BANK_ACCOUNT) {
	$response['code'] = INVALID_BANK_ACCOUNT;
	$response['error'] = true;
	$response['message'] = getMessage($lang_code, "INVALID_BANK_ACCOUNT");
	$response['data'] = array();
    }  else if ($res == SUCCESSFULLY_DONE) {
		$response['code'] = SUCCESSFULLY_DONE;
		$response['error'] = true;
		$response['message'] = getMessage($lang_code, "BANK_DETAILS_SAVED");
		$response['data'] = array();
    } else {
		if(isset($res['msg']) && !empty($res['msg'])){
			$errorMsg = $res['msg'];
			$response['code'] = INVALID_BANK_ACCOUNT;
			$response['error'] = false;
			$response['message'] = $errorMsg;
			$response['data'] = array();
		}else{
			$response['code'] = UNABLE_TO_PROCEED;
			$response['error'] = true;
			$response['message'] = getMessage($lang_code, "UNABLE_TO_PROCEED");
			$response['data'] = array();
		}
    }
    echoRespnse(HTTP_RESPONSE_CODE_OK, $response);
});

/**
 * User add credit card
 * url - /add_credit_card
 * method - POST
 * params -  card_holder_name(mandatory), card_number(mandatory), cvv(mandatory), expiry_date(mandatory(m/Y)), currency(mandatory)(send "USD" for now), is_default(mandatory)(Y=>yes, N=>No)
 * header Params - email (mandatory), password (mandatory)
 */
$app->post('/add_credit_card', 'authenticate', function () use ($app) {
    global $user_id;
	global $lang_code;
    verifyRequiredParams(array('card_holder_name', 'card_number', 'cvv', 'expiry_date', 'currency', 'is_default'));
	
	$params["card_holder_name"] = $app->request()->post('card_holder_name');
	$params["card_number"] 		= $app->request()->post('card_number');
	$params["cvv"]				= $app->request()->post('cvv');
	$params["expiry_date"] 		= $app->request()->post('expiry_date');
	$params["currency"] 		= $app->request()->post('currency');
	$params["is_default"] 		= $app->request()->post('is_default');
	
	$params["user_id"] 			= $user_id;
	
    $response = array();
    $db = new DbHandler();
    $user = $db->addCreditCardStripe($params, $lang_code);

    if ($user==INVALID_DATE) {
        $response['code'] = INVALID_DATE;
        $response['error'] = true;
		$response['message'] = getMessage($lang_code, "INVALID_DATE");
    } else if ($user==INVALID_USER) {
        $response['code'] = INVALID_USER;
        $response['error'] = true;
		$response['message'] = getMessage($lang_code, "INVALID_USER");
    } else {
		if(isset( $user['msg']) && !empty( $user['msg'])){
			$errorMsg = $user['msg'];
			$response['code'] = INVALID_BANK_ACCOUNT;
			$response['error'] = true;
			$response['message'] = $errorMsg;
			$response['data'] = array();
		}
		else{
			$response['code'] = 0;
			$response['error'] = false;
			$response['message'] = "Card successfully added";
			$response['data'] = $user;
		}
    }
    echoRespnse(200, $response);
});
/* 
	*Driver  Detail
*/
$app->get('/driver_detail', function() use ($app) {
	$user_id = $_GET['id'];

    $db = new DbHandler();
    $r = $db->get_driver_details($user_id);
    echoRespnse(200, $r);
});
/* 
	*Customer Image Upload 
*/
$app->post('/customer_profile_image','authenticate', function() use ($app) {

	$customer_id = $_POST['customer_id'];
	$image = $_FILES['image'];
	$ex1 = explode(".", $image['name']);
    $ext = end($ex1);
    $user_image = 'customer -' .time().'.' .$ext;
    $upload = move_uploaded_file($image['tmp_name'], '../app/webroot/uploads/customer_documents/'. $user_image);
	if (file_exists('../app/webroot/uploads/customer_documents/' . $user_image)) {
	    $db = new DbHandler();
    	$r = $db->upload_customer_image($customer_id,$user_image);
	}
    
    echoRespnse(200, $r);
});
/*
	* GET User Data
*/
$app->get('/user/:id','authenticate', function($user_id) use ($app) {

	$db = new DbHandler();
    $r = $db->get_user_data($user_id);	
    
    echoRespnse(200, $r);
});
$app->run();
