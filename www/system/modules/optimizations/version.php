<?php
return [
    'version' => '1.4',
    'ModuleInfo' => [
        'name' => 'optimizations',
        'version_description' => 'sCacheHtml',
        'link_home' => '/optimizations',
    ],
    'Folders' => [ // папки для архивации. По умолчанию system/modules/NameModule и www/templates/modules/NameModule
        'system/modules/optimizations',
        'www/templates/modules/optimizations',
    ],
    /*'requireModules' => [ // дополнительные модули, которые требует данный модуль чтобы работать. По умолчанию их нет
        [
            'name_module' => 'slider',
            'version' => '3',
        ],
    ],*/
];
