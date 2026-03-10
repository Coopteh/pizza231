<?php
return [
    'GET' => [
        '/' => ['HomeController', 'index'],
        '/services' => ['ServicesController', 'index'],
        '/quote' => ['QuoteController', 'create'],
        '/claims' => ['ClaimsController', 'index'],
    ],
    'POST' => [
        '/quote' => ['QuoteController', 'store'],
        '/claims' => ['ClaimsController', 'store'],
    ]
];