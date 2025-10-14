<?php
namespace toubilib\core\application\ports\api\dtos;

use toubilib\core\domain\entities\user\User;

class ProfilDTO{
    public int $id;
    public string $email;
    public string $password;
    public string $role;

    public function __construct(User $user) {
        $this->id = $user->id;
        $this->email = $user->email;
        $this->password = $user->password;
        $this->role = $user->role;
    }
}