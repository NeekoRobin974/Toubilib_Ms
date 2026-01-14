<?php

namespace toubilib\core\application\ports\api\dtos;

class CredentialsDTO{

    public function __construct(
        public  string $email,
        public  string $password,
        public  int $role
    ){}
}