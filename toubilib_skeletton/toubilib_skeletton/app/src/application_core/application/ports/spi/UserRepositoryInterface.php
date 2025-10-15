<?php
namespace toubilib\core\application\ports\spi;

use toubilib\core\domain\entities\user\User;

interface UserRepositoryInterface{
    public function findByEmail(string $email): ?User;
    public function findById(string $id): ?User;
}
