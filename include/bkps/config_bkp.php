<?php
############ Database Constants Start #################

$db_host = 'localhost';
$db_username = 'root';
$db_password = '123456';
$db_name = 'pida';
if('m.brsoftech.net' == $_SERVER['HTTP_HOST']){
    $db_host = 'localhost';
    $db_username = 'BrdbUserm';
    $db_password = 'BrDB!34*908m';
    $db_name = 'pida';
}

define('DB_HOST', $db_host);
define('DB_USERNAME', $db_username);
define('DB_PASSWORD', $db_password);
define('DB_NAME', $db_name);

############ Database Constants End #################

############ Message Constants Start #################

define('USER_CREATED_SUCCESSFULLY', 0);
define('USER_CREATE_FAILED', 1);
define('EMAIL_ALREADY_EXISTED', 2);
define('EMAIL_ALREADY_EXISTED_MSG', 'Email already exists');
define('USERNAME_ALREADY_EXISTED', 3);

define('USER_ACCOUNT_DEACTVATED', 4);
define('INVALID_EMAIL_PASSWORD', 5);

define('INVALID_EMAIL', 6);
define('UNABLE_TO_PROCEED', 7);
define('UNABLE_TO_PROCEED_MSG', 'Unable to proceed');
define('SUCCESSFULLY_DONE', 8);
define('SUCCESSFULLY_DONE_MSG', 'Success');

define('INVALID_OLD_PASSWORD', 9);
define('INVALID_USER', 10);

define('PROFILE_UPDATED_SUCCESSFULLY', 11);

define('ALREADY_EXIST', 12);
define('ALREADY_REPLIED', 13);

define('INVALID_REQUEST', 14);
define('NEED_PASSWORD', 15);

define('PHONE_ALREADY_EXISTED', 16);
define('PHONE_ALREADY_EXISTED_MSG', 'Phone number already exists');
define('SSN_ALREADY_EXISTED', 17);
define('DRIVING_LICENSE_ALREADY_EXISTED', 18);
define('NO_RECORD_FOUND', 19);
define('NO_RECORD_FOUND_MSG', 'No Record Found');
define('DRIVER_ACCOUNT_DEACTVATED', 20);
define('DRIVER_ACCOUNT_DEACTVATED_MSG', 'we are reviewing your details , you will get response within 1-2 business days');
define('INSURANCE_POLICY_DOC_SIZE', 21);
define('INSURANCE_POLICY_DOC_SIZE_MSG', 'Max size of insurance policy doc can be 2 mb');
define('INSURANCE_POLICY_DOC_TYPE', 22);
define('INSURANCE_POLICY_DOC_TYPE_MSG', 'Only image,doc and pdf are allowed for insurance policy doc');
define('REGISTRATION_DOC_SIZE', 23);
define('REGISTRATION_DOC_SIZE_MSG', 'Max size of registration doc can be 2 mb');
define('REGISTRATION_DOC_TYPE', 24);
define('REGISTRATION_DOC_TYPE_MSG', 'Only image,doc and pdf are allowed for registration doc');

define('INVALID_ARRIVE_TYPE', 25);
define('INVALID_ARRIVE_TYPE_MSG', 'Only Source,destination are allowed');

define('LICENSE_DOC_SIZE', 23);
define('LICENSE_DOC_SIZE_MSG', 'Max size of license doc can be 2 mb');
define('LICENSE_DOC_TYPE', 24);
define('LICENSE_DOC_TYPE_MSG', 'Only image,doc and pdf are allowed for license doc');
define('USER_ALREADY_RATE', 25);
define('USER_ALREADY_RATE_MSG', 'User had already given rating');
define('BOOKING_CANCEL_ERROR', 26);
define('BOOKING_CANCEL_ERROR_MSG', 'Booking cannot be cancelled');
define('BOOKING_COMPLETED_ARRIVED_ERROR', 27);
define('BOOKING_COMPLETED_ARRIVED_ERROR_MSG', 'Booking can not be completed because driver did not arrived');

define('INVALID_ACCOUNT_TYPE', 28);
define('INVALID_ACCOUNT_TYPE_MSG', 'Only Saving,Current are allowed');

define('INVALID_BANK_ACCOUNT', 29);
define('INVALID_BANK_ACCOUNT_MSG', 'Invalid bank account details');
define('INVALID_DRIVER_BANK_ACCOUNT', 30);
define('INVALID_DRIVER_BANK_ACCOUNT_MSG', 'Driver bank account details not found');

define('HTTP_RESPONSE_CODE_OK', 200);
define('HTTP_RESPONSE_CODE_CREATED', 201);



############ Message Constants End #################


############ Defining Path Constants Start #################
$project_directory = '/pida/';

define('APIURL', 'http://'.$_SERVER['HTTP_HOST'] . $project_directory);

define('APIPATH', $_SERVER['DOCUMENT_ROOT'] . $project_directory);

//User Photos
define('USER_PHOTO_PATH', APIPATH . 'app/webroot/uploads/user_photos/');
define('USER_PHOTO_PATH_LARGE', APIPATH . 'app/webroot/uploads/user_photos/large/');
define('USER_PHOTO_PATH_THUMB', APIPATH . 'app/webroot/uploads/user_photos/thumbnail/');
define('USER_PHOTO_URL', APIURL . 'uploads/user_photos/');
define('USER_PHOTO_URL_LARGE', APIURL . 'uploads/user_photos/large/');
define('USER_PHOTO_URL_THUMB', APIURL . 'uploads/user_photos/thumbnail/');

//Vehivle Type Images
define('VEHICLE_TYPE_IMG_PATH', APIPATH . 'app/webroot/uploads/vehicle_type/');
define('VEHICLE_TYPE_IMG_PATH_LARGE', APIPATH . 'app/webroot/uploads/vehicle_type/large/');
define('VEHICLE_TYPE_IMG_PATH_THUMB', APIPATH . 'app/webroot/uploads/vehicle_type/thumbnail/');
define('VEHICLE_TYPE_IMG_URL', APIURL . 'uploads/webroot/uploads/vehicle_type/');
define('VEHICLE_TYPE_IMG_URL_LARGE', APIURL . 'uploads/vehicle_type/large/');
define('VEHICLE_TYPE_IMG_URL_THUMB', APIURL . 'uploads/vehicle_type/thumbnail/');

//Driver Docs
define('DRIVER_DOC_PATH', APIPATH . 'app/webroot/uploads/driver_documents/');
define('DRIVER_DOC_PATH_LARGE', APIPATH . 'app/webroot/uploads/driver_documents/large/');
define('DRIVER_DOC_PATH_THUMB', APIPATH . 'app/webroot/uploads/driver_documents/thumbnail/');
define('DRIVER_DOC_URL', APIURL . 'uploads/driver_documents/');
define('DRIVER_DOC_URL_LARGE', APIURL . 'uploads/driver_documents/large/');
define('DRIVER_DOC_URL_THUMB', APIURL . 'uploads/driver_documents/thumbnail/');

//Vehicle Docs
define('VEHICLE_DOC_PATH', APIPATH . 'app/webroot/uploads/vehicle_documents/');
define('VEHICLE_DOC_PATH_LARGE', APIPATH . 'app/webroot/uploads/vehicle_documents/large/');
define('VEHICLE_DOC_PATH_THUMB', APIPATH . 'app/webroot/uploads/vehicle_documents/thumbnail/');
define('VEHICLE_DOC_URL', APIURL . 'uploads/vehicle_documents/');
define('VEHICLE_DOC_URL_LARGE', APIURL . 'uploads/vehicle_documents/large/');
define('VEHICLE_DOC_URL_THUMB', APIURL . 'uploads/vehicle_documents/thumbnail/');

############ Defining Path Constants End #################

define('PIDA_COMMISSION', 30);
define('NEAR_BY_MILES', 5);
define('DEFAULT_LANGUAGE', 'en');
define('CRON_JOB_DURATION_PENDING_REQUEST', 1); 
define('CRON_JOB_DURATION_BOOKING_SCHEDULE_REQUEST', 120); 
define('CRON_JOB_DURATION_AUTO_LOGOUT_DRIVER', 5); 


############ Push Notification Constants Start #################
define('BOOKING_REQUEST_DRIVER', 1);
define('BOOKING_REQUEST_ACCEPT_USER', 2);
define('BOOKING_LOCATION_SOURCE_USER', 3);
define('BOOKING_LOCATION_DESTINATION_USER', 4);
define('BOOKING_REQUEST_REJECT_USER', 5);
define('BOOKING_PAYMENT_NOTIFY_USER', 6);
define('BOOKING_PAYMENT_NOTIFY_DRIVER', 7);
define('BOOKING_CANCEL_NOTIFY_DRIVER', 8);
define('ALREADY_LOGIN_NOTIFY_USER_DRIVER', 9);


############ Push Notification Constants End #################

############ App keys Constants Start #################
define('ANDRIOD_PUSH_KEY', 'AIzaSyCW981YREW1E2f9blBNiFp2vzHaaw95uus');
define('IPHONE_PASS_PHRASE', '123456');
define('IPHONE_PEM_FILE', 'ck.pem');

define('BRAINTREE_ENVIRONMENT', 'sandbox');
define('BRAINTREE_MERCHANTID', '2vjfty3ghx6dsmqf');
define('BRAINTREE_MERCHANT', 'brsoftech');
define('BRAINTREE_PUBLICKEY', 'zn6byvff3bt4dzxq');
define('BRAINTREE_PRIVATEKEY', '2582b95781804073dcdaf72108da9ea3');
############ App keys Constants End #################






