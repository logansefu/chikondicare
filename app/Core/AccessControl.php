<?php
namespace App\Core;

class AccessControl {
    public static function require(string $permission): void {
        Auth::requireLogin();
        $db = Database::connection();
        $q = $db->prepare("SELECT COUNT(*) FROM role_permissions rp JOIN permissions p ON p.id=rp.permission_id WHERE rp.role_id=? AND p.code=?");
        $q->execute([Auth::user()['role_id'], $permission]);
        if (!(int)$q->fetchColumn()) {
            http_response_code(403);
            exit('You are not authorised to access this function.');
        }
    }
}
