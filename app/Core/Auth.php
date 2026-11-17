<?php
namespace App\Core;

use PDO;

class Auth {
    public static function attempt(string $username, string $password): bool {
        $db = Database::connection();
        $q = $db->prepare("SELECT u.*, r.name AS role_name FROM users u JOIN roles r ON r.id=u.role_id WHERE u.username=? AND u.is_active=1 LIMIT 1");
        $q->execute([$username]);
        $user = $q->fetch();
        if (!$user || !password_verify($password, $user['password_hash'])) {
            self::failedLogin($username);
            return false;
        }
        if ((int)$user['locked_until'] > time()) return false;
        $db->prepare("UPDATE users SET failed_attempts=0, locked_until=NULL WHERE id=?")->execute([$user['id']]);
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int)$user['id'], 'username' => $user['username'],
            'name' => $user['full_name'], 'role' => $user['role_name'],
            'role_id' => (int)$user['role_id'], 'must_change_password' => (bool)$user['must_change_password']
        ];
        $db->prepare("INSERT INTO audit_logs(user_id, action, details, ip_address) VALUES(?,?,?,?)")
           ->execute([$user['id'], 'LOGIN', 'Successful login', $_SERVER['REMOTE_ADDR'] ?? '']);
        return true;
    }
    private static function failedLogin(string $username): void {
        $db = Database::connection();
        $q = $db->prepare("SELECT id,failed_attempts FROM users WHERE username=?");
        $q->execute([$username]);
        if ($u = $q->fetch()) {
            $n = (int)$u['failed_attempts'] + 1;
            $lock = $n >= 5 ? time() + 300 : null;
            $db->prepare("UPDATE users SET failed_attempts=?,locked_until=? WHERE id=?")->execute([$n,$lock,$u['id']]);
        }
    }
    public static function check(): bool { return isset($_SESSION['user']); }
    public static function user(): ?array { return $_SESSION['user'] ?? null; }
    public static function requireLogin(): void {
        if (!self::check()) { header('Location: /login.php'); exit; }
        if (!empty($_SESSION['user']['must_change_password']) && basename($_SERVER['PHP_SELF']) !== 'change_password.php') {
            header('Location: /change_password.php'); exit;
        }
    }
    public static function logout(): void {
        if (self::check()) {
            Database::connection()->prepare("INSERT INTO audit_logs(user_id,action,details,ip_address) VALUES(?,?,?,?)")
                ->execute([self::user()['id'],'LOGOUT','User logged out',$_SERVER['REMOTE_ADDR'] ?? '']);
        }
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time()-42000, $p['path'], $p['domain'] ?? '', $p['secure'], $p['httponly']);
        }
        session_destroy();
    }
}
