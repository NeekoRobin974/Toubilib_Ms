<?php

namespace toubilib\api\provider;


use toubilib\core\application\ports\api\dtos\CredentialsDTO;
use toubilib\core\application\ports\api\dtos\ProfilDTO;

interface AuthnProviderInterface {
    public function register(CredentialsDTO $credentials): ProfilDTO;
    public function signin(CredentialsDTO $credentials): array;
}