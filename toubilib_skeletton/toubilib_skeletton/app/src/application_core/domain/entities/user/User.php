<?php
namespace toubilib\core\domain\entities\user;

class User{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
    ];

    public string $id;
    public string $email;
    public string $password;
    public string $role;
}