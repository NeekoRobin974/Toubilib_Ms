<?php

namespace toubilib\core\application\ports\api;


use toubilib\core\application\ports\api\dtos\CredentialsDTO;
use toubilib\core\application\ports\api\dtos\ProfilDTO;

interface ServiceUserInterface{

    public function register(CredentialsDTO $credentials): ProfilDTO;
    public function byCredentials(CredentialsDTO $credentials): ?ProfilDTO;
}