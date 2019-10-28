<?php

return [
    '__name' => 'site-event',
    '__version' => '0.0.1',
    '__git' => 'git@github.com:getmim/site-event.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'http://iqbalfn.com/'
    ],
    '__files' => [
        'app/site-event' => ['install','remove'],
        'modules/site-event' => ['install','update','remove'],
        'theme/site/event' => ['install','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'event' => NULL
            ],
            [
                'site' => NULL
            ],
            [
                'site-meta' => NULL
            ],
            [
                'lib-formatter' => NULL
            ]
        ],
        'optional' => [
            [
                'lib-event' => NULL
            ],
            [
                'lib-cache-output' => NULL
            ],
            [
                'site-setting' => NULL
            ]
        ]
    ],
    'autoload' => [
        'classes' => [
            'SiteEvent\\Controller' => [
                'type' => 'file',
                'base' => ['app/site-event/controller','modules/site-event/controller']
            ],
            'SiteEvent\\Library' => [
                'type' => 'file',
                'base' => 'modules/site-event/library'
            ],
            'SiteEvent\\Meta' => [
                'type' => 'file',
                'base' => 'modules/site-event/meta'
            ]
        ],
        'files' => []
    ],
    'routes' => [
        'site' => [
            'siteEventIndex' => [
                'path' => [
                    'value' => '/event'
                ],
                'handler' => 'SiteEvent\\Controller\\Event::index'
            ],
            'siteEventSingle' => [
                'path' => [
                    'value' => '/event/read/(:slug)',
                    'params' => [
                        'slug' => 'slug'
                    ]
                ],
                'handler' => 'SiteEvent\\Controller\\Event::single'
            ],
            'siteEventFeed' => [
                'path' => [
                    'value' => '/event/feed.xml'
                ],
                'method' => 'GET',
                'handler' => 'SiteEvent\\Controller\\Robot::feed'
            ]
        ]
    ],
    'libFormatter' => [
        'formats' => [
            'event' => [
                'page' => [
                    'type' => 'router',
                    'router' => [
                        'name' => 'siteEventSingle',
                        'params' => [
                            'slug' => '$slug'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'libEvent' => [
        'events' => [
            'event:created' => [
                'SiteEvent\\Library\\Event::clear' => TRUE
            ],
            'event:deleted' => [
                'SiteEvent\\Library\\Event::clear' => TRUE
            ],
            'event:updated' => [
                'SiteEvent\\Library\\Event::clear' => TRUE
            ]
        ]
    ],
    'site' => [
        'robot' => [
            'feed' => [
                'SiteEvent\\Library\\Robot::feed' => TRUE
            ],
            'sitemap' => [
                'SiteEvent\\Library\\Robot::sitemap' => TRUE
            ]
        ]
    ]
];