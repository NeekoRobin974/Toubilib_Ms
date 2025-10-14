<?php
namespace toubilib\api\provider;

use toubilib\api\provider\AuthnProviderInterface;
use toubilib\core\application\usecases\AuthnServiceInterface;
use toubilib\core\domain\entities\user\User;

//Fournisseur d'authentification pour la gestion des utilisateurs connectés
class AuthnProvider implements AuthnProviderInterface {
    protected AuthnServiceInterface $authnService;

    public function __construct(AuthnServiceInterface $authnService) {
        $this->authnService = $authnService;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    //Récup l'utilisateur connecté
    public function getSignedInUser(): ?User {
        if (!isset($_SESSION['id'])) {
            return null;
        }
        return User::query()->find($_SESSION['id']);
    }

    //Récup le role de l'utilisateur connecté
    public function getRoleUser(): ?int {
        if (!isset($_SESSION['id'])) {
            return null;
        }
        return User::query()->find($_SESSION['id'])->role ?? null;
    }

    //Tente de connecter l'utilisateur avec ses identifiants
    public function signin(string $email, string $password): bool {
        try {
            $user = $this->authnService->verifyCredentials($email, $password);
            //Stock les infos de l'utilisateur dans la session
            $_SESSION['id'] = $user->id;
            $_SESSION['email'] = $user->email;
            $_SESSION['role'] = $user->role;
            return true;
        } catch (\Exception $e) {
            error_log("Erreur de connexion: " . $e->getMessage());
            return false;
        }
    }

    //Déconnecte l'utilisateur en supprimant ses infos de la session
    public function signout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['id']);
        unset($_SESSION['email']);
        unset($_SESSION['role']);
    }

    //Vérif si l'utilisateur est connecté
    public function isSignedIn(): bool {
        return isset($_SESSION['id']);
    }
}