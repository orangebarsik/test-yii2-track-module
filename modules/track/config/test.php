<?php
return [
    'id' => 'app-track-test',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\modules\track\controllers',
    'components' => [
        'db' => require __DIR__ . '/db_test.php',
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ],
];