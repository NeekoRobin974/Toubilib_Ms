<?php
namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\spi\UserRepositoryInterface;
use toubilib\core\application\ports\api\dtos\ProfilDTO;
use toubilib\core\application\ports\api\AuthnServiceInterface;
use toubilib\core\domain\entities\user\User;

class AuthnService implements AuthnServiceInterface{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository){
        $this->userRepository = $userRepository;
    }

    public function verifyCredentials(string $email, string $password): ?User {
        $user = $this->userRepository->findByEmail($email);
        if (!$user) {
            return null;
        }

        var_dump($password);
        var_dump($user->password);
        if (password_verify($password, $user->password)) {
            return $user;
        }
        return null;
    }

    public function getUserById(string $id): ?User{
        return $this->userRepository->findById($id);
    }
}