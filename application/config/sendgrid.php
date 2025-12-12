<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['sendgrid'] = [
    'api_key'   => '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',  // sebaiknya pakai env
    'from_email'=> 'mail@anam.ch',
    'from_name' => 'Anam',
    'endpoint'  => 'https://api.sendgrid.com/v3/mail/send',
];