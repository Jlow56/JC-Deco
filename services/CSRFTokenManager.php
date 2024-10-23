<?php
class CSRFTokenManager {
    // Génère un jeton CSRF et le stocke dans la session
    public function generateCSRFToken() : string
    {
        // Génère un token aléatoire
        $token = bin2hex(random_bytes(32));

        // Stocke le token dans la session avec un timestamp pour gérer l'expiration
        $_SESSION['csrf-token'] = $token;
        $_SESSION['csrf-token-time'] = time(); // Stocke le temps de génération du token

        return $token;
    }

    // Valide un jeton CSRF
    public function validateCSRFToken($token) : bool
    {
        // Vérifie si le jeton existe dans la session et s'il correspond
        if (isset($_SESSION['csrf-token']) && hash_equals($_SESSION['csrf-token'], $token))
        {
            // Vérifie si le token a expiré (par exemple, 10 minutes de validité)
            if (time() - $_SESSION['csrf-token-time'] > 600) 
            { // 600 secondes = 10 minutes
                unset($_SESSION['csrf-token']); // Supprime le token expiré
                return false; // Le token a expiré
            }
            return true; // Le token est valide et non expiré
        }
        return false; // Le token est invalide
    }
}