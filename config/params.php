<?php

$bitrixParams = require __DIR__ . '/bitrix_params.php';

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'bitrixParams' => $bitrixParams,
];
