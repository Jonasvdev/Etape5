<?php
require_once __DIR__ . '/../config/database.php';

class User
{
    private PDO $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() !== false;
    }

    public function register(string $username, string $email, string $password): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password)
             VALUES (:username, :email, :password)'
        );
        return $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
        ]);
    }

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function verifyPassword(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }



public function getAll(): array
{
    $stmt = $this->db->query('SELECT id, username, email, is_admin FROM users');
    return $stmt->fetchAll();
}

public function findById(int $id): array|false
{
    $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}

public function update(int $id, string $username, string $email, bool $isAdmin): bool
{
    $stmt = $this->db->prepare(
        'UPDATE users SET username = :username, email = :email, is_admin = :is_admin WHERE id = :id'
    );
    return $stmt->execute([
        'username' => $username,
        'email' => $email,
        'is_admin' => $isAdmin ? 1 : 0,
        'id' => $id,
    ]);
}

public function delete(int $id): bool
{
    $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
    return $stmt->execute(['id' => $id]);
}


// Partie de modifiction de mot de passe

public function updatePassword(int $id, string $newPassword): bool
{
    $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
    $stmt = $this->db->prepare('UPDATE users SET password = :password WHERE id = :id');
    return $stmt->execute(['password' => $hashed, 'id' => $id]);
}

// AVATAR UTILISATEUR
public function updateAvatar(int $id, string $avatarPath): bool
{
    $stmt = $this->db->prepare('UPDATE users SET avatar = :avatar WHERE id = :id');
    return $stmt->execute(['avatar' => $avatarPath, 'id' => $id]);
}

}




