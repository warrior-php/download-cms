<?php
declare(strict_types=1);

use support\Request;

return [
    'debug'             => true,
    'error_reporting'   => E_ALL,
    'default_timezone'  => 'Asia/Shanghai',
    'request_class'     => Request::class,
    'public_path'       => base_path() . DIRECTORY_SEPARATOR . 'public',
    'runtime_path'      => base_path(false) . DIRECTORY_SEPARATOR . 'runtime',
    'controller_suffix' => '',
    'controller_reuse'  => false,
];
