<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

define('IS_FRONTEND',true);

$backend = dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.BACKOFFICE_FOLDER.DIRECTORY_SEPARATOR."protected";
$backend_webroot = dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.BACKOFFICE_FOLDER.DIRECTORY_SEPARATOR;
$upload_dir = dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'upload';
$home_dir = dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR;
Yii::setPathOfAlias('backend',$backend);
Yii::setPathOfAlias('backend_webroot',$backend_webroot);

$modules_dir = dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'protected/modules';

require_once $backend."/components/RedisHelper.php";

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Karenderia Multiple Restaurant',
	
	'aliases' => array(
       'upload_dir' => $upload_dir,   
       'modules_dir'=> $modules_dir,
       'home_dir' => $home_dir,
    ),

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.modules.*',
		'application.components.*',
		'application.components.jobs.*',
		'application.controllers.*',
		'application.vendor.*',
		'application.extensions.*',
		'application.extensions.EHttpClient.*',
		'backend.components.*',		
		'backend.vendor.*',
		'backend.models.*',
		'ext.YiiMailer.YiiMailer'
	),
	
	'modules'=>array(
        'cod'=>array(),
        'ocr'=>array(),
        'paypal'=>array(),
        'stripe'=>array(),
        'cybersource'=>array(),
        'razorpay'=>array(),
        'mercadopago'=>array(),
		'bank'=>array(),
		'jad'=>array(),
		'voguepay'=>array(),
		'flutterwave'=>array(),
		'paystack'=>array(),
		'scanpay'=>array(),
		'billplz'=>array(),
		'toyyibpay'=>array(),
		'sofort'=>array(),
		'sofort_ideal'=>array(),
		'payhere'=>array(),
		'iyzipay'=>array(),
		'qmoney'=>array(),
		'braintree'=>array(),
		'payumoney'=>array(),
		'stripe_connect'=>array(),
		'cybersource_connect'=>array(),
		'everypay'=>array(),
		'clover'=>array(),
		'viva'=>array(),
		'revolut'=>array(),
		'paytabs'=>array(),
		'kotak'=>array(),
		'vivawallet'=>array(),
		'tap'=>array(),
		'knet'=>array(),
		'stripehosted'=>array(),
		'cybersourcehosted'=>array(),
		'paymongo'=>array(),
		'dpo'=>array(),
		'paydelivery'=>array(),
		'myfatoorah'=>array(),
		'digital_wallet'=>array(),
		'paygreen'=>array(),
		'cmi'=>array(),
		'redsys'=>array(),
		'bizum'=>array(),
		'dojo'=>array(),
    ),

	'defaultController'=>'store',
	
	'theme'=>'karenderia_v2',

	'language'=>KMRS_DEFAULT_LANGUAGE,
	
	'sourceLanguage'=>"en_us",
	
	'timezone'=>"Asia/Manila",

	// application components
	'components'=>array(
		// 'session'=>array(
        //     'timeout' => 3600,
        //     'autoStart'=>true,
        // ),

	    'cache' => RedisHelper::isRedisAvailable() ? array(
            'class' => 'CRedisCache',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
        ) : array(
            'class' => 'CFileCache',  // Fallback cache method, e.g., file-based cache
        ),
		
		 // use language file in database
	    'messages'=>array(
	      'class'=>'CDbMessageSource',
	      'cacheID'=>'cache',
	      'cachingDuration'=>1,
	      'sourceMessageTable'=>'{{sourcemessage}}',
	      'translatedMessageTable'=>'{{message}}'
	    ),
		
	    'request'=>array(
	        'class'=>'HttpRequest',
            'enableCsrfValidation'=>true,
            'enableCookieValidation'=>true,
            'noCsrfValidationRoutes'=>array(
                'stripe/webhooks',
                'cybersource/*',
                'cybersource/webhooks',
				'app/*',
				'partnerapi/*',
				'pv1/*',
				'interface/*',
				'payv1/*',
				'everypay/webhooks',
				'clover/webhooks',
				'viva/verifypayment',
				'driver/*',
				'interfacemerchant/*',
				'interfacesubscription/*',
				'flutterwave/*',
				'paytabs/apipaytabs/*',
				'paytabs/verify/*',
				'paytabs/ipn',
				'apibooking/*',
				'driverpayment/*',
				'vivawallet/api/verifypayment',
				'vivawallet/apiapp/verifypayment',
				'vivawallet/webhook',
				'tap/api/postpayment',
				'tap/webhook',
				'knet/verifypayment',
				'knet/postpayment',
				'stripehosted/webhooks',
				'cybersourcehosted/webhooks',
				'paymongo/webhooks',
				'myfatoorah/webhooks',
				'chatapi/*',
				'paygreen/webhooks',
				'apipos/*',
				'cmi/api/*',
				'redsys/api/*',
				'bizum/api/*',
				'tasksms/webhook',
				'apitable/*',
				'paypal/apiv2*',
                'stripe/apiv2*',
                'cybersource/apiv2*',
				'razorpay/apiv2*',
				'mercadopago/apiv2*',
				'apikitchen/*',
				'dojo/api/webhooks',
				'jad/api*',
             ),
        ),
	   
		'user'=>array(			
			'allowAutoLogin'=>true,			
			'class'=>"WebUserCustomer",
			'loginUrl'=>array('/account/login'),
		),

		'merchant'=>array(			
			'allowAutoLogin'=>true,			
			'class'=>"WebUserMerchant",
			'loginUrl'=>array('/auth/login'),
		),	

		'driver'=>array(			
			'allowAutoLogin'=>true,			
			'class'=>"WebUserDriver",
			'loginUrl'=>array('/auth/login'),
		),	
		
		'db'=>array(
			//'connectionString' => 'mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME,
			'connectionString' => 'mysql:host='.DB_HOST.';dbname='.DB_NAME,
			'emulatePrepare' => true,
			'username' => DB_USER,
			'password' => DB_PASSWORD,
			'charset' => DB_CHARSET,
			'tablePrefix' => DB_PREFIX,
			'schemaCachingDuration'=>60,
			'initSQLs' => array(
                "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))",
				//"SET SESSION group_concat_max_len=4294967295"
            ),
		),
		'errorHandler'=>array(			
			'errorAction'=>'store/pagenotfound',
		),
		'urlManager'=>array(
			'urlFormat'=>'path',			
			'showScriptName'=>false,
			'caseSensitive'=>false,
			'rules'=>array(
			    array(
			        'class' => 'application.components.CustomUrlRule',
			        'connectionID' => 'db',
			    ),
			    //'<action:\w+>'=>"store/<action>",
			    ''=>'store/index',
				'sitemap.xml' => 'sitemap/index',
			    'account/notifications-list'=>"account/notificationslist",
			    'merchant/user-signup'=>"merchant/usersignup",
			    'merchant/payment-processing'=>"merchant/paymentprocessing",
			    'merchant/signup-failed'=>"merchant/signupfailed",
			    'merchant/cashin-successful'=>"merchant/cashin_successful",
			    '<action:(restaurants|offers|pagenotfound|feed|contactus|search)>' => 'store/<action>',
			    '<controller:\w+>/<action:\w+>/id/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
				
				[
					'class' => 'CFileLogRoute',
					'levels' => 'info',
					'logFile' => 'cybersource-webhook.log'
				],
			),
		),
		'input'=>array(
		   'class'=>'CmsInput',
		   'cleanPost'=>true,
		   'cleanGet'=>true
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);