<?php
declare(strict_types=1);

return [
    'enable'     => true,
    'middleware' => [
        App\Middleware\StaticFile::class, // Static file Middleware
    ],
];