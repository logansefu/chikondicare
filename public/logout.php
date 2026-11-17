<?php
require __DIR__.'/_init.php';
use App\Core\Auth;
Auth::logout(); header('Location: /login.php'); exit;
