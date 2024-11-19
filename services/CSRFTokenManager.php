<?php
class CSRFTokenManager extends AbstractController
{
    private const TOKEN_EXPIRATION_TIME = 600; // 10 minutes

    // Génère un jeton CSRF et le stocke dans la session
    public function generateCSRFToken(): string
    {
        // Vérifie que la session est démarrée
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $token = bin2hex(random_bytes(32)); // Génération sécurisée
        $_SESSION['csrf-token'] = $token;
        $_SESSION['csrf-token-time'] = time(); // Stocke l'heure de génération

        return $token;
    }

    // Valide un jeton CSRF
    public function validateCSRFToken(?string $token): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (isset($_SESSION['csrf-token'], $_SESSION['csrf-token-time']) && hash_equals($_SESSION['csrf-token'], $token)) {
            // Vérifie la validité dans le temps
            if (time() - $_SESSION['csrf-token-time'] <= self::TOKEN_EXPIRATION_TIME) {
                return true; // Token valide
            }
            // Nettoyage des tokens expirés
            unset($_SESSION['csrf-token'], $_SESSION['csrf-token-time']);
        }
        // Action en cas de token invalide ou expiré
        $_SESSION['timeOut'] = "Le token n'est plus valide, veuillez vous reconnecter.";
        $this->redirect("login");
        return false; // Ne sera pas atteint si la redirection est exécutée
    }
}