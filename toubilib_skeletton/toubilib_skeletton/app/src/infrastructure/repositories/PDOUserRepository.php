<?php

namespace toubilib\infra\repositories;

use toubilib\core\application\ports\spi\UserRepositoryInterface;
use toubilib\core\domain\entities\user\User;

class PDOUserRepository implements UserRepositoryInterface{
    private \PDO $pdo;

    public function __construct(\PDO $pdo){
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email): ?User{
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) return null;

        $user = new User();
        $user->id = $row['id'];
        $user->email = $row['email'];
        $user->password = $row['password'];
        $user->role = (string)$row['role'];
        return $user;
    }

    public function findById(string $id): ?User{
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) return null;

        $user = new User();
        $user->id = $row['id'];
        $user->email = $row['email'];
        $user->password = $row['password'];
        $user->role = (string)$row['role'];
        return $user;
    }
}