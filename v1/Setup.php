<?php
namespace Test;

require_once __DIR__ . '/Helper.php';
require_once __DIR__ . '/integration/HttpClientApi.php';
require_once __DIR__ . '/integration/SubscriptionHelper.php';
require_once __DIR__ . '/Braintree/CreditCardNumbers/CardTypeIndicators.php';
require_once __DIR__ . '/Braintree/CreditCardDefaults.php';
require_once __DIR__ . '/Braintree/OAuthTestHelper.php';

date_default_timezone_set('UTC');

use Braintree\Configuration;
use PHPUnit_Framework_TestCase;

class Setup /*extends PHPUnit_Framework_TestCase*/
{
    public function __construct()
    {
        self::integrationMerchantConfig();
    }

    public static function integrationMerchantConfig()
    {
        Configuration::reset();

        Configuration::environment(BRAINTREE_ENVIRONMENT);
        Configuration::merchantId(BRAINTREE_MERCHANTID);
        Configuration::publicKey(BRAINTREE_PUBLICKEY);
        Configuration::privateKey(BRAINTREE_PRIVATEKEY);

    }
}
