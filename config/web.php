<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'track',
	'language' => 'ru-RU',
	'name'=>'Управление посылками',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'imBuFQVV2zf8FgKerJ1-6Cj8RBFJNvcf',
			'enableCsrfValidation'=> false, // Для API обычно отключают CSRF
			'parsers' => [
				'application/json' => 'yii\web\JsonParser', // Включить обработку JSON
			],
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
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,    // Включить ЧПУ
			'showScriptName' => false,    // Скрыть index.php
			'enableStrictParsing' => true, // Строгий разбор URL (опционально)
            'rules' => [
				// Главная страница
				'' => 'track/track/index',
				
				// Правила для Посылки
				'track/<action>' => 'track/track/<action>',
				
				// Правила для Логов
				'track-logs' => 'track/track-log/index',
				'track-logs/<id:\d+>' => 'track/track-log/view',
				
				// REST API правила
				[
					'class' => 'yii\rest\UrlRule',
					'controller' => 'track/api/track',
					'pluralize' => false, // Отключаем автоматическое множественное число
					'extraPatterns' => [
						'POST bulk-update' => 'bulk-update', // Добавляем кастомный экшен
					],
				],
				
				// Дополнительные правила
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
    ],
	'modules' => [
		'track' => [
			'class' => 'app\modules\track\Module',
		],
	],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
		'allowedIPs' => ['192.168.1.204', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
		'allowedIPs' => ['192.168.1.204', '::1'],
		
    ];
}

return $config;
