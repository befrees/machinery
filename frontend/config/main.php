<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'homeUrl' => '/',
    'timeZone' => 'Europe/Minsk',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'controllerMap' => [],
        ],
    ],
    'components' => [
        'request' => [
            'baseUrl' => '',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'cart' => [
            'class' => 'common\modules\store\components\Cart',
        ],
        'urlManager' => [
            'rules' => [
                ['class' => 'frontend\components\AliasRule'],
                'user/<userId:\d+>/wish' => 'store/wish',
                'user/<userId:\d+>/wish/remove/<id:\d+>' => 'store/wish/remove'
            ],
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@frontend/views/user'
                ],
            ],
        ],
    ],
    'params' => $params,
];
