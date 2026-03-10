<?php
use App\Controllers\HomeController;
use App\Controllers\CoursesController;
use App\Controllers\EnrollmentController;

$routes = [
    '/' => ['controller' => HomeController::class, 'action' => 'index'],
    '/courses' => ['controller' => CoursesController::class, 'action' => 'index'],
    '/enrollment' => ['controller' => EnrollmentController::class, 'action' => 'create'],
];