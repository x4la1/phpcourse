<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Controller\UserController;
$UserController = new UserController();
$UserController->updateUser($_POST, $_FILES);