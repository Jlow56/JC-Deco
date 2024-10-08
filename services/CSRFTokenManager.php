<?php
// Classe CSRFTokenManager pour gérer les jetons CSRF
class CSRFTokenManager {
    // Génère un jeton CSRF
    public function generateCSRFToken() : string
    {
        // Génère un jeton aléatoire de 32 octets et le convertit en une chaîne hexadécimale
        $token = bin2hex(random_bytes(32));
        return $token;
    }

    // Valide un jeton CSRF
    public function validateCSRFToken($token) : bool
    {
        // Vérifie si le jeton CSRF en session existe et s'il correspond au jeton fourni
        if (isset($_SESSION['csrf-token']) && hash_equals($_SESSION['csrf-token'], $token)) {
            return true;
        } else {
            return false;
        }
    }
}
