<?php
class Database
{
    private string $host = 'localhost';
    private string $dbname = 'mon_projet';
    private string $user = 'root';
    private string $password = '';
    private ?PDO $connection = null;

    public function getConnection(): PDO {

        if ($this->connection === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";

            $this->connection = new PDO($dsn, $this->user, $this->password, [
                
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }
        return $this->connection;
    }
}





