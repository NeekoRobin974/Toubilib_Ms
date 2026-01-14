<?php
namespace toubilib\core\application\ports\spi;

use toubilib\core\domain\entities\user\User;

interface AuthRepositoryInterface
{
    public function findById(string $id): ?User;
}