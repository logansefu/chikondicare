<?php
require dirname(__DIR__).'/_init.php';use App\Core\AccessControl;use App\Core\Database;use App\Core\CSRF;
AccessControl::require('appointments.manage');if($_SERVER['REQUEST_METHOD']!=='POST')exit('Method not allowed');CSRF::verify();
$q=Database::connection()->prepare("UPDATE appointments SET status='cancelled',cancelled_at=NOW(),cancellation_reason=? WHERE id=? AND status='booked'");
$q->execute([trim($_POST['reason']??''),(int)($_POST['id']??0)]);header('Location: /appointments/index.php');exit;
