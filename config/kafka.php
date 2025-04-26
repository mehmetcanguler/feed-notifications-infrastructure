<?php

return [

    'brokers' => env('KAFKA_BROKERS', 'localhost:9092'),

    'consumer_group_id' => env('KAFKA_CONSUMER_GROUP_ID', 'default'),

    'sasl' => [
        'enabled' => env('KAFKA_SASL_ENABLED', false),
        'username' => env('KAFKA_SASL_USERNAME', null),
        'password' => env('KAFKA_SASL_PASSWORD', null),
        'mechanisms' => env('KAFKA_SASL_MECHANISMS', 'PLAIN'),
    ],

    'security_protocol' => env('KAFKA_SECURITY_PROTOCOL', 'PLAINTEXT'),

    'auto_commit' => env('KAFKA_AUTO_COMMIT', true),

    'sleep_on_error' => env('KAFKA_SLEEP_ON_ERROR', 5),

    'commit' => [
        'batch_size' => env('KAFKA_COMMIT_BATCH_SIZE', 1),
    ],
];
