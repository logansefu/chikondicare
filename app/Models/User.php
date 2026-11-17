<?php
namespace App\Models;
use App\Core\Database;
use PDO;

class User {
    public static function create(array $d): int {
        $db=Database::connection();
        $q=$db->prepare("INSERT INTO users(username,full_name,password_hash,role_id,must_change_password) VALUES(?,?,?,?,1)");
        $q->execute([$d['username'],$d['full_name'],password_hash($d['password'],PASSWORD_DEFAULT),$d['role_id']]);
        return (int)$db->lastInsertId();
    }
}
