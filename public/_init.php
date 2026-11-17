<?php
require dirname(__DIR__).'/bootstrap.php';
use App\Core\Auth;
use App\Core\AccessControl;
use App\Core\Database;
use App\Core\CSRF;
use App\Core\Validator;
use App\Exceptions\ValidationException;
