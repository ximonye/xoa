<?php
return [
    'id' => 'xoa-cli',
    'basePath' => PROJECT_PATH . '/server/console',
    'runtimePath' => '@app/../runtime',
    'controllerNamespace' => 'xoa\console\commands',
	'aliases' => [
		'@tests' => PROJECT_PATH . '/server/tests/codeception',
	],
	'controllerMap' => [
		'migrate' => [
			'class' => 'xoa\common\ext\controllers\MigrateController',
		],
	],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'xoa\common\ext\log\FileLog',
                    'levels' => ['error', 'warning', 'info'],
					'logFile' => '@runtime/logs/console-' . date('Y-m-d') . '.log'
                ],
            ],
        ],
        'db' => include(PROJECT_PATH . '/server/common/config/db.php'),
    ],
];