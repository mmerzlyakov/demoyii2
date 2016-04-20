<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'view' => [
            'renderers' => [
                'tpl' => [
                    'class' => 'yii\smarty\ViewRenderer',
                    //'cachePath' => '@runtime/Smarty/cache',
                ],
                'html' => [
                    'class' => 'yii\smarty\ViewRenderer',
                    //'cachePath' => '@runtime/Smarty/cache',
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'dOUdMUqVGVrjNidVJzRoPir9EOkh68kL',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
 		'urlManager' => [
	        'class' => 'yii\web\UrlManager',
        	// Disable index.php
	        'showScriptName' => false,
	        // Disable r= routes
	        'enablePrettyUrl' => true,
	        'rules' => array(

                        'catalog' => 'catalog/index',
                        'catalog/index' => 'catalog/index',
                        'catalog/<alias:.+>'=>'catalog/view',
        		        '<controller:\w+>/<id:\d+>' => '<controller>/view',
		                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
		                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                        'signup'=>'site/signup',
                        'submitsignup'=>'site/submitsignup',
                        'login'=>'site/login',
                        'submitlogin'=>'site/submitlogin',
		        ),
        ],
		 'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
