<?php
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/test_db.php';

/**
 * Application configuration shared by all test types
 */
return [
    'id' => 'track-tests',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'ru-RU',
	'name'=>'Управление посылками',
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
        'db' => $db,
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
            'messageClass' => 'yii\symfonymailer\Message'
        ],
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
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
