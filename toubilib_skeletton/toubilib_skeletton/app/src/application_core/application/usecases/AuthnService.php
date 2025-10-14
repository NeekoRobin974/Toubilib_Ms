<?php

namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\spi\PraticienRepositoryInterface;
use toubilib\core\application\usecases\exceptions\InsufficientRightsAuthzException;
use toubilib\core\application\usecases\exceptions\InvalidRoleAuthzException;
use toubilib\core\application\usecases\exceptions\NotOwnerAuthzException;

class AuthzPraticienService {
    const OPERATION_READ = 1;
    const OPERATION_UPDATE = 2;
    const OPERATION_DELETE = 100;
    const OPERATION_CREATE = 4;
    const OPERATION_LIST = 5;
    const ROLE_PRATICIEN = 10;
    const ROLE_ADMIN = 100;

    private ?PraticienRepositoryInterface $praticienRepository;

    public function __construct(?PraticienRepositoryInterface $praticienRepository = null) {
        $this->praticienRepository = $praticienRepository;
    }

    public function isGranted(
        string $user_id,
        int $role,
        string $ressource_id,
        int $operation = self::OPERATION_READ
    ): bool {
        if ($role < self::ROLE_PRATICIEN) {
            throw new InvalidRoleAuthzException('Role invalide');
        }
        if ($user_id !== $ressource_id) {
            throw new NotOwnerAuthzException('Vous netes pas le bon praticien');
        }
        if ($operation === self::OPERATION_DELETE) {
            throw new InsufficientRightsAuthzException('Droits insuffisants');
        }
        return true;
    }
}