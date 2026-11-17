<?php
require dirname(__DIR__).'/bootstrap.php';
use App\Core\Database;
$db=Database::connection();
$role=(int)$db->query("SELECT id FROM roles WHERE name='Administrator'")->fetchColumn();
$q=$db->prepare("SELECT COUNT(*) FROM users WHERE username='admin'");
if ((int)$q->fetchColumn() > 0) exit("Admin already exists.\n");
$db->prepare("INSERT INTO users(username,full_name,password_hash,role_id,must_change_password) VALUES(?,?,?,?,1)")
   ->execute(['admin','System Administrator',password_hash('ChangeMe123!',PASSWORD_DEFAULT),$role]);
echo "Administrator created. Username: admin Password: ChangeMe123!\n";
