<?php
namespace toubilib\core\application\ports\api\dtos;

class AuthDTO{
    public function __construct(
        public string $accesToken,
        public string $refreshToken
    )
    {}
}