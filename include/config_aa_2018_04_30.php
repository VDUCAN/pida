<?php
############ Database Constants Start #################
$db_host = '192.168.1.253';
$db_username = 'root';
$db_password = '123456';
$db_name = 'pida';
$project_directory = '/pida/';
$iphonePushUrl = 'ssl://gateway.sandbox.push.apple.com:2195';
if(strpos($_SERVER['HTTP_HOST'], 'pida') !== false){
    $db_host = '23.239.20.78';
    $db_username = 'pida2';
    $db_password = 'Pida@Mysql#@!123';
    $db_name = 'pida';
    $project_directory = '/';
    $iphonePushUrl = 'ssl://gateway.push.apple.com:2195';
	
	define('BRAINTREE_ENVIRONMENT', 'production');
	define('BRAINTREE_MERCHANTID', 'nhdpfb6gqrst9fks');
	define('BRAINTREE_MERCHANT', 'PIDACORP_instant');
	define('BRAINTREE_PUBLICKEY', 'gs6g7362ryqr582b');
	define('BRAINTREE_PRIVATEKEY', 'a298d8971748a36b1a8b5d6191224db9'); 
	// Url : https://braintreegateway.com/login
	// u/p : info@pida.com/TmppidaapP310%
	
}elseif(strpos($_SERVER['HTTP_HOST'], 'brsoftech') !== false){
	$db_host = 'm.brsoftech.net';
    $db_username = 'BrdbUserm';
    $db_password = 'BrDB!34*908m';
    $db_name = 'pida';
	
	define('BRAINTREE_ENVIRONMENT', 'sandbox');
	define('BRAINTREE_MERCHANTID', 'wyzcrvhyxy9yx485');
	define('BRAINTREE_MERCHANT', 'brsoftechpvtltd');
	define('BRAINTREE_PUBLICKEY', 'bqjm7n75mbpbc37s');
	define('BRAINTREE_PRIVATEKEY', '0b534787b068b14339aa81d34409d8b3'); 
	// Url : https://sandbox.braintreegateway.com/login
	// u/p : developerbrsoft3@gmail.com/brsoftech@123
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
define('EMAIL_ALREADY_EXISTED_MSG_ES', 'Correo ya existe');

define('USERNAME_ALREADY_EXISTED', 3);

define('USER_ACCOUNT_DEACTVATED', 4);
define('USER_ACCOUNT_DEACTVATED_MSG', "User account deactivated");
define('USER_ACCOUNT_DEACTVATED_MSG_ES', "Cuenta desativado");

define('INVALID_EMAIL_PASSWORD', 5);
define('INVALID_EMAIL_PASSWORD_MSG', "Invalid Email Id or Password");
define('INVALID_EMAIL_PASSWORD_MSG_ES', "Correo o contrasena invalido");

define('INVALID_EMAIL', 6);
define('INVALID_EMAIL_MSG', "Email address does not exist");
define('INVALID_EMAIL_MSG_ES', "Correo no existe");


define('UNABLE_TO_PROCEED', 7);
define('UNABLE_TO_PROCEED_MSG', 'Unable to proceed');
define('UNABLE_TO_PROCEED_MSG_ES', 'No se puede continuar su solicitud');

define('SUCCESSFULLY_DONE', 8);
define('SUCCESSFULLY_DONE_MSG', 'Success');
define('SUCCESSFULLY_DONE_MSG_ES', 'Suceso');

define('INVALID_OLD_PASSWORD', 9);
define('INVALID_OLD_PASSWORD_MSG', "Invalid old password");
define('INVALID_OLD_PASSWORD_MSG_ES', "contrasena vieja invalido");

define('INVALID_USER', 10);
define('INVALID_USER_MSG', "Invalid user access");
define('INVALID_USER_MSG_ES', "Usuario invalido");

define('PROFILE_UPDATED_SUCCESSFULLY', 11);
define('PROFILE_UPDATED_SUCCESSFULLY_MSG', "User Profile successfully updated");
define('PROFILE_UPDATED_SUCCESSFULLY_MSG_ES', "Perfil de usuario actualizado con éxito");


define('ALREADY_EXIST', 12);
define('ALREADY_REPLIED', 13);

define('INVALID_REQUEST', 14);
define('INVALID_REQUEST_MSG', "Invalid Login");
define('INVALID_REQUEST_MSG_ES', "Cuenta invalido");

define('NEED_PASSWORD', 15);
define('NEED_PASSWORD_MSG', "Need password for login from App");
define('NEED_PASSWORD_MSG_ES', "Se requiere contrasena para iniciar sesion");

define('PHONE_ALREADY_EXISTED', 16);
define('PHONE_ALREADY_EXISTED_MSG', 'Phone number already exists');
define('PHONE_ALREADY_EXISTED_MSG_ES', 'Numero ya existe');

define('SSN_ALREADY_EXISTED', 17);
define('SSN_ALREADY_EXISTED_MSG', "SSN already exists");
define('SSN_ALREADY_EXISTED_MSG_ES', "SNN ya existe");

define('DRIVING_LICENSE_ALREADY_EXISTED', 18);
define('DRIVING_LICENSE_ALREADY_EXISTED_MSG', "Driving license number already exists");
define('DRIVING_LICENSE_ALREADY_EXISTED_MSG_ES', "Licencia ya existe");

define('NO_RECORD_FOUND', 19);
define('NO_RECORD_FOUND_MSG', 'No Record Found');
define('NO_RECORD_FOUND_MSG_ES', 'No se encontro registro');

define('DRIVER_ACCOUNT_DEACTVATED', 20);
define('DRIVER_ACCOUNT_DEACTVATED_MSG', 'We are reviewing your details , you will get response within 1-2 business days');
define('DRIVER_ACCOUNT_DEACTVATED_MSG_ES', 'Estamos revisando sus detalles, usted recibirá la respuesta dentro de 1-2 días hábiles');

define('INSURANCE_POLICY_DOC_SIZE', 21);
define('INSURANCE_POLICY_DOC_SIZE_MSG', 'Max size of insurance policy doc can be 2 mb');
define('INSURANCE_POLICY_DOC_SIZE_MSG_ES', 'El tamaño máximo de la póliza de seguro puede ser de 2 mb');

define('INSURANCE_POLICY_DOC_TYPE', 22);
define('INSURANCE_POLICY_DOC_TYPE_MSG', 'Only image,doc and pdf are allowed for insurance policy doc');
define('INSURANCE_POLICY_DOC_TYPE_MSG_ES', 'Sólo se permiten imágenes, doc y pdf para el documento de póliza de seguro');

define('REGISTRATION_DOC_SIZE', 23);
define('REGISTRATION_DOC_SIZE_MSG', 'Max size of registration doc can be 2 mb');
define('REGISTRATION_DOC_SIZE_MSG_ES', 'El tamaño máximo del documento de registro puede ser 2 mb');

define('REGISTRATION_DOC_TYPE', 24);
define('REGISTRATION_DOC_TYPE_MSG', 'Only image,doc and pdf are allowed for registration doc');
define('REGISTRATION_DOC_TYPE_MSG_ES', 'Solo se permiten imágenes, doc y pdf para el documento de registro');

define('INVALID_ARRIVE_TYPE', 25);
define('INVALID_ARRIVE_TYPE_MSG', 'Only Source,destination are allowed');
define('INVALID_ARRIVE_TYPE_MSG_ES', 'Solo se permiten fuentes, destino');

define('LICENSE_DOC_SIZE', 23);
define('LICENSE_DOC_SIZE_MSG', 'Max size of license doc can be 2 mb');
define('LICENSE_DOC_SIZE_MSG_ES', 'El tamaño máximo del documento de licencia puede ser de 2 mb');

define('LICENSE_DOC_TYPE', 24);
define('LICENSE_DOC_TYPE_MSG', 'Only image,doc and pdf are allowed for license doc');
define('LICENSE_DOC_TYPE_MSG_ES', 'Solo se permiten imágenes, doc y pdf para el documento de licencia');

define('USER_ALREADY_RATE', 25);
define('USER_ALREADY_RATE_MSG', 'User had already given rating');
define('USER_ALREADY_RATE_MSG_ES', 'El usuario ya ha dado una calificación');

define('BOOKING_CANCEL_ERROR', 26);
define('BOOKING_CANCEL_ERROR_MSG', 'Booking cannot be cancelled driver arrived at source');
define('BOOKING_CANCEL_ERROR_MSG_ES', 'Reserva no se puede cancelar el conductor llegó a la fuente');

define('BOOKING_COMPLETED_ARRIVED_ERROR', 27);
define('BOOKING_COMPLETED_ARRIVED_ERROR_MSG', 'Booking can not be completed because driver did not arrived');
define('BOOKING_COMPLETED_ARRIVED_ERROR_MSG_ES', 'La reserva no se puede completar porque el conductor no llegó');

define('INVALID_ACCOUNT_TYPE', 28);
define('INVALID_ACCOUNT_TYPE_MSG', 'Only Saving,Current are allowed');
define('INVALID_ACCOUNT_TYPE_MSG_ES', 'Solo se permiten ahorros, corrientes');

define('INVALID_BANK_ACCOUNT', 29);
define('INVALID_BANK_ACCOUNT_MSG', 'Invalid bank account details');
define('INVALID_BANK_ACCOUNT_MSG_ES', 'Detalles de la cuenta bancaria no válida');

define('INVALID_DRIVER_BANK_ACCOUNT', 30);
define('INVALID_DRIVER_BANK_ACCOUNT_MSG', 'Driver bank account details not found');
define('INVALID_DRIVER_BANK_ACCOUNT_MSG_ES', 'Detalles de la cuenta bancaria del conductor no encontrados');

define('BOOKING_CANCEL_TIME_ERROR', 31);
define('BOOKING_CANCEL_TIME_ERROR_MSG', 'Booking cannot be cancelled because 15 minutes have been past from booking time');
define('BOOKING_CANCEL_TIME_ERROR_MSG_ES', 'No se puede cancelar la reserva porque han pasado 15 minutos desde el momento de la reserva.');

define('BOOKING_CANCEL_RIDER_TIME_ERROR', 32);
define('BOOKING_CANCEL_RIDER_TIME_ERROR_MSG', 'Booking cannot be cancelled because 15 minutes are remaining from booking time');
define('BOOKING_CANCEL_RIDER_TIME_ERROR_MSG_ES', 'No se puede cancelar la reserva porque faltan 15 minutos para reservar');

define('HTTP_RESPONSE_CODE_OK', 200);
define('HTTP_RESPONSE_CODE_CREATED', 201);

define('PASSWORD_CHANGE_SUCCESS_MSG', 'Password successfully changed.');
define('PASSWORD_CHANGE_SUCCESS_MSG_ES', 'Su contransena nueva ha sido cambiado');

define('LOCATION_UPDATE_SUCCESS_MSG', 'Location successfully updated.');
define('LOCATION_UPDATE_SUCCESS_MSG_ES', 'Localizacion ha sido actualizado');

define('SEARCH_DRIVER_MSG', 'No nearby drivers are available Please wait we are searching nearby driver.');
define('SEARCH_DRIVER_MSG_ES', 'No hay conductor cerca en su area, favor esperar estamos buscando.');

define('NO_NEARBY_DRIVER_MSG', 'No nearby drivers are available. Please try later.');
define('NO_NEARBY_DRIVER_MSG_ES', 'No hay conductor disponible. Favor intentar mas tarde.');

define('BOOKING_SUCCESS_MSG', 'Booking sucessfully done.');
define('BOOKING_SUCCESS_MSG_ES', 'Reserva suceso.');

define('FEEDBACK_DONE_MSG', 'Booking sucessfully done.');
define('FEEDBACK_DONE_MSG_ES', 'Comentario ha sido enviado');

define('DRIVER_VEHICLE_ADDED_MSG', 'Driver vehicle added sucessfully.');
define('DRIVER_VEHICLE_ADDED_MSG_ES', 'Vehiculo agregado');

define('UPCOMING_BOOKING_MSG', 'Upcoming bookings');
define('UPCOMING_BOOKING_MSG_ES', 'Reserva proximo');

define('USER_TYPE_CHANGED_MSG', 'User type changed sucessfully.');
define('USER_TYPE_CHANGED_MSG_ES', 'Su tipo de cuenta se cambio suceso');

define('DRIVER_ARRIVED_MSG', 'Driver has been arrived.');
define('DRIVER_ARRIVED_MSG_ES', 'Conductor ha llegado.');

define('DISTANCE_LAT_LONG_MSG', 'Distance between lat long');
define('DISTANCE_LAT_LONG_MSG_ES', 'Distancia en latitud loguitud');

define('BOOKING_COMPLETE_MSG', 'Booking has been completed');
define('BOOKING_COMPLETE_MSG_ES', 'Reservacion se ha completado');

define('BOOKING_RATED_MSG', 'Booking has been rated');
define('BOOKING_RATED_MSG_ES', 'Reservacion ha sido evaluado');

define('CREDIT_CARD_DETAILS_MSG', 'Credit card details');
define('CREDIT_CARD_DETAILS_MSG_ES', 'Detalle de tarjeta credito');

define('CREDIT_CARD_SET_DEFAULT_MSG', 'Credit card has been set default');
define('CREDIT_CARD_SET_DEFAULT_MSG_ES', 'Como tajerta principal');

define('BOOKING_CANCELLED_MSG', 'Booking has been cancelled');
define('BOOKING_CANCELLED_MSG_ES', 'Reservacion cancelado');

define('LOGOUT_SUCCESS_MSG', 'User logout successfully');
define('LOGOUT_SUCCESS_MSG_ES', 'Usted ha cerrado sesion');

define('BOOKING_DETAILS_MSG', 'Booking details');
define('BOOKING_DETAILS_MSG_ES', 'Detalles de la reserva');

define('BANK_DETAILS_MSG', 'Bank details');
define('BANK_DETAILS_MSG_ES', 'Detalles del banco');

define('BANK_DETAILS_SAVED_MSG', 'Bank account details have been saved');
define('BANK_DETAILS_SAVED_MSG_ES', 'Los detalles de la cuenta bancaria se han guardado');

define('CREDIT_CARD_ADDED_MSG', 'Credit card added');
define('CREDIT_CARD_ADDED_MSG_ES', 'Tarjeta de crédito añadida');

define('REPORTED_SUCCESS_MSG', 'Successfully reported');
define('REPORTED_SUCCESS_MSG_ES', 'Reportado con éxito');


define('INVALID_DATE', 33);
define('INVALID_DATE_MSG', 'Invalid date!');
define('INVALID_DATE_MSG_ES', '¡Fecha inválida!');

########## PUSH NOTIFICATION CONSTANT MESSAGES ###########

define('NEW_BOOKING_REQUEST_MSG', 'You have new booking request');
define('NEW_BOOKING_REQUEST_MSG_ES', 'Tienes nueva solicitud de reserva');

define('DRIVER_ACCEPT_BOOKING_REQUEST_MSG', 'Driver has accepted the booking request');
define('DRIVER_ACCEPT_BOOKING_REQUEST_MSG_ES', 'El conductor ha aceptado la solicitud de reserva');

define('DRIVER_PICKED_REQUEST_MSG', 'Driver is on the way to pick up your order');
define('DRIVER_PICKED_REQUEST_MSG_ES', 'El conductor está en camino para recoger su pedido');

define('REACHED_DRIVER_MSG', 'Driver is on the way to pick up your order');
define('REACHED_DRIVER_MSG_ES', 'El conductor está en camino para recoger su pedido');

define('DRIVER_DROP_OFF_MSG', 'Driver has arrived to the destination ');
define('DRIVER_DROP_OFF_MSG_ES', 'El conductor ha llegado al destino ');

define('DRIVER_PICKUP_MSG', 'Driver is on the way to delivery your order');
define('DRIVER_PICKUP_MSG_ES', 'El conductor está en el camino de entrega que usted ordena');

define('NO_DRIVER_AVAILABLE_MSG', 'No driver is available for your last booking');
define('NO_DRIVER_AVAILABLE_MSG_ES', 'No hay conductor disponible para su última reserva');
 
define('CREDIT_CARD_CHARGED_MSG', 'Your order has been delivered.Your credit card has been charged an amount of $ {price} for booking');
define('CREDIT_CARD_CHARGED_MSG_ES', 'Su tarjeta de crédito se ha cargado por el importe de {price} para la reserva');

define('AMOUNT_RECEIVED_MSG', 'You have received an amount of {price} for booking');
define('AMOUNT_RECEIVED_MSG_ES', 'Has recibido una cantidad de {price} reservar');

define('USER_CANCEL_BOOKING_MSG', 'User has cancelled booking');
define('USER_CANCEL_BOOKING_MSG_ES', 'El usuario ha cancelado la reserva');

define('LOGIN_OTHER_DEVICE_MSG', 'You had login from other device');
define('LOGIN_OTHER_DEVICE_MSG_ES', 'Tienes acceso desde otro dispositivo');


########## PUSH NOTIFICATION CONSTANT MESSAGES ENDS###########


############ Message Constants End #################


############ Defining Path Constants Start #################


define('APIURL', 'http://'.$_SERVER['HTTP_HOST'] . $project_directory);

define('APIPATH', $_SERVER['DOCUMENT_ROOT'] . $project_directory);

define('ROOT_DIRECTORY', $_SERVER['DOCUMENT_ROOT'] . $project_directory);


define('PHPMAILER_LIB', APIPATH."lib/phpmailer/vendor/autoload.php");
//phpmailer settings
defined('SMTP_SERVER') or define('SMTP_SERVER', 'smtp.sendgrid.net');
defined('SMTP_USERNAME') or define('SMTP_USERNAME', 'Pidaapp');
defined('SMTP_PASSWORD') or define('SMTP_PASSWORD', 'pidaappgo2Y7nLZ');
defined('SMTP_PORT') or define('SMTP_PORT', 587);
defined('SMTP_SECURE') or define('SMTP_SECURE', 'tls');//ssl or tls 

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
define('CRON_JOB_DURATION_PENDING_REQUEST', 3); 
define('CRON_JOB_DURATION_BOOKING_SCHEDULE_REQUEST', 60); 
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
define('NAVIGATE_REACHED_DRIVER', 10);
define('NAVIGATE_START_DRIVER', 11);
define('PICKUP_REACHED_DRIVER', 12);


############ Push Notification Constants End #################

############ App keys Constants Start #################
define('ANDRIOD_PUSH_KEY', 'AIzaSyCW981YREW1E2f9blBNiFp2vzHaaw95uus');
define('IPHONE_PASS_PHRASE', '123456');
define('IPHONE_PEM_FILE', 'pushpida.pem');
define('IPHONE_PUSH_URL', $iphonePushUrl);

/*
define('BRAINTREE_ENVIRONMENT', 'sandbox');
define('BRAINTREE_MERCHANTID', 'xgqkn2svtnsfnjpv');
define('BRAINTREE_MERCHANT', 'pida_app');
define('BRAINTREE_PUBLICKEY', 'h9fcy669h2bwqy24');
define('BRAINTREE_PRIVATEKEY', 'e3d94a57393c86f3866f42b95de4f8d0'); */



############ App keys Constants End #################



################## STRIPE CREDENTIALS  ###################
define("STRIPE_AUTOLOAD", APIPATH."lib/stripe/vendor/autoload.php");
define("STRIPE_PUBLISHABLE_KEY", "pk_test_QtsMZAPCPJbMVAVPFBmNYxnv");
define("STRIPE_SECRET_KEY", "sk_test_XjW1Yacbzyx3sEbvaNPuA7BH");
define("STRIPE_CLIENT_ID", "ca_CeAb3LZJEeaDgTc8xuwTLY7aRRqvBMBe");





