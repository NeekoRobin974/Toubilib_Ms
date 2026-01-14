<?php

namespace toubilib\core\application\usecases;



use toubilib\core\application\ports\api\dtos\ProfilDTO;
use toubilib\core\application\ports\api\dtos\CredentialsDTO;
use toubilib\core\application\ports\api\ServiceUserInterface;
use toubilib\core\application\ports\spi\AuthRepositoryInterface;

class ServiceUser implements ServiceUserInterface {

    private AuthRepositoryInterface $userRepository;

    public function __construct(AuthRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(CredentialsDTO $credentials): ProfilDTO
    {

        $this->userRepository->save($credentials);
        $user = $this->userRepository->findByEmail($credentials->email);

        return new ProfilDTO(
            $user->getId(),
            $user->getEmail(),
            $user->getRole()
        );
    }

    public function byCredentials(CredentialsDTO $credentials): ?ProfilDTO{
        $user = $this->userRepository->findByEmail($credentials->email);
        if($user === null){
            throw new \Exception("Email éroné");
        }

        if(!password_verify($credentials->password, $user->getPassword())){
            throw new \Exception("Mot de passe éroné");
        }

        return new ProfilDTO(
            $user->getId(),
            $user->getEmail(),
            $user->getRole()
        );
    }
}