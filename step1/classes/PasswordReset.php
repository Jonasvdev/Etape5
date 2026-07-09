<?php
require_once __DIR__ . '/../config/database.php';

class PasswordReset
{
    private PDO $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function createToken(int $userId): string
    {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $this->db->prepare(
            'INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)'
        );
        $stmt->execute(['user_id' => $userId, 'token' => $token, 'expires_at' => $expiresAt]);

        return $token;
    }

    public function findValidToken(string $token): array|false
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM password_resets WHERE token = :token AND expires_at > NOW()'
        );
        $stmt->execute(['token' => $token]);
        return $stmt->fetch();
    }

    public function deleteByUserId(int $userId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM password_resets WHERE user_id = :user_id');
        return $stmt->execute(['user_id' => $userId]);
    }
}




