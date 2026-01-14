<?php
namespace toubilib\api\provider;

use toubilib\core\application\ports\api\AuthnServiceInterface;
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
        return $this->authnService->getUserById($_SESSION['id']);
    }

    //Récup le role de l'utilisateur connecté
    public function getRoleUser(): ?int {
        if (!isset($_SESSION['id'])) {
            return null;
        }
        return User::query()->find($_SESSION['id'])->role ?? null;
    }

    //Tente de connecter l'utilisateur avec ses identifiants
    public function signin(string $email, string $password): ?array{
        try {
            $user = $this->authnService->verifyCredentials($email, $password);
            if (!$user) return null;
            $_SESSION['id'] = $user->id;
            $_SESSION['email'] = $user->email;
            $_SESSION['role'] = $user->role;

            $profil = new \toubilib\core\application\ports\api\dtos\ProfilDTO($user);
            $accessToken = $this->generateAccessToken($user);
            $refreshToken = $this->generateRefreshToken($user);

            return [
                'profil' => $profil,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken
            ];
        } catch (\Exception $e) {
            error_log("Erreur de connexion: " . $e->getMessage());
            return null;
        }
    }

    //Génère un token d'accès JWT
    private function generateAccessToken(User $user): string {
        return bin2hex(random_bytes(16));
    }

    //Génère un token de rafraîchissement
    private function generateRefreshToken(User $user): string {
        return bin2hex(random_bytes(32));
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