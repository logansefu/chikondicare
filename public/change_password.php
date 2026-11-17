<?php
require __DIR__.'/_init.php';
use App\Core\Auth; use App\Core\Database; use App\Core\CSRF;
Auth::requireLogin(); $error=''; $ok='';
if($_SERVER['REQUEST_METHOD']==='POST'){CSRF::verify();$p=$_POST['password']??'';
if(strlen($p)<10){$error='Use at least 10 characters.';}elseif($p!==($_POST['confirm']??'')){$error='Passwords do not match.';}else{
Database::connection()->prepare("UPDATE users SET password_hash=?,must_change_password=0 WHERE id=?")->execute([password_hash($p,PASSWORD_DEFAULT),Auth::user()['id']]);
$_SESSION['user']['must_change_password']=false;header('Location: /dashboard.php');exit;}}
?>
<!doctype html><html><head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="container py-5"><div class="card p-4 mx-auto" style="max-width:480px"><h3>Change your password</h3><p>You must replace your initial password before continuing.</p><?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?><form method="post"><?=CSRF::field()?><input class="form-control mb-3" type="password" name="password" placeholder="New password" required><input class="form-control mb-3" type="password" name="confirm" placeholder="Confirm password" required><button class="btn btn-primary">Update password</button></form></div></body></html>
