<?php
return [
    'settings' => [
        'db_auth' => [
            'dsn' => 'pgsql:host=toubiauth.db;dbname=toubiauth',
            'user' => 'toubiauth',
            'password' => 'toubiauth'
        ],
        'jwt' => [
            'key' => 'clef',
            'alg' => 'HS256'
        ],
        'displayErrorDetails' => true,
    ],
];