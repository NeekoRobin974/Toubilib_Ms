<?php
namespace toubilib\api\provider;

use toubilib\core\domain\entities\user\User;

interface AuthnProviderInterface {
    public function getSignedInUser(): ?User;
    public function signin(string $email, string $password): bool;
    public function signout(): void;
    public function isSignedIn(): bool;
}