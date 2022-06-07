<?php
///App::uses('AppController','Controller');

class V1Controller extends AppController{
    
    public function verifyRequiredParams($required_fields) {
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
    
   public function login(){  
    //   die('here');
    $this->verifyRequiredParams(array('email', 'login_from', 'device_type', 'device_token', 'user_type'));
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

}
    
}
?>