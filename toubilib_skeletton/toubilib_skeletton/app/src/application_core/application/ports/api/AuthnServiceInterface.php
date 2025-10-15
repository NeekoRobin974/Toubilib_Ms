<?php
namespace toubilib\core\application\ports\api;

use toubilib\core\application\ports\api\dtos\ProfilDTO;
use toubilib\core\domain\entities\user\User;

interface AuthnServiceInterface {
    public function verifyCredentials(string $email, string $password): ?User;
    public function getUserById(string $id): ?User;
}