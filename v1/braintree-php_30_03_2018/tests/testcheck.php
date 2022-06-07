<?php


//	require_once 'Setup.php';
	require_once '../lib/Braintree.php';

	Braintree_Configuration::environment('sandbox');
	Braintree_Configuration::merchantId('2vjfty3ghx6dsmqf');
	Braintree_Configuration::publicKey('zn6byvff3bt4dzxq');
	Braintree_Configuration::privateKey('2582b95781804073dcdaf72108da9ea3');

	$result = Braintree_Transaction::sale([
  'amount' => '10.00',
  'paymentMethodNonce' => 'fake-valid-nonce',
  'options' => [
    'submitForSettlement' => True
  ]
]);

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


	echo 'test';
	die;

	




?>