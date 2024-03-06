<?php

return [
    '/login' => '/site/login',
    '/login-password' => '/site/login-password',
    '/logout' => '/site/logout',
    '/request-password-reset' => '/site/request-password-reset',
    '/reset-password' => '/site/reset-password',
    'transport/<action>' => 'transport/default/<action>',
    'autobot/<action>' => 'autobot/default/<action>',
    'file/<action>' => 'file/default/<action>',
    '/' => '/site/index',
    'help/wiki/crud/<action>' => 'wiki/crud/<action>',
    'help/wiki/crud/<action>/<id:\d+>' => 'wiki/crud/<action>',
    'help/wiki/download/<id:\d+>' => '/wiki/default/download/',
    'help/wiki/<parents:[\w_\/-]+>/<slug:[\w]+>'=>'/wiki/default/view/',
    'help/wiki/<slug:\w+>/<version:\d.+>' => '/wiki/default/slug/',
    'help/wiki/<slug:\w+>' => '/wiki/default/view/',
    'help/wiki' => '/wiki/default/index',
    'wiki/set-widget-article' => '/wiki/default/set-article-widget-id',
    'wiki/get-section' => '/wiki/default/get-section-by-id',
    'wiki/get-article' => '/wiki/default/get-article-by-id',
    'settings/export-xml' => 'settings/settings-xml',
    /*
      'login' => 'site/login',
      'login-password' => 'site/login-password',
      'logout' => 'site/logout',
     */
    'tour/<id:\w+>' => 'tour',
    'monitor/<action>' => 'monitor/default/<action>',
    //'monitor/notifications' => 'monitor/notifications/index',
    'api/v1/export/confirm' => 'api/v1/confirm-export'
];
