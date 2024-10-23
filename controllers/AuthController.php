<?php
class AuthController extends AbstractController
{
    public function login(): void
    {
        $tokenManager = new CSRFTokenManager();
        $csrfToken = $tokenManager->generateCSRFToken();

        $this->render("admin/login.html.twig", ['sessionToken' => $csrfToken]);
    }

    public function Checklogin(): void
    {
        if (!isset($_POST["email"], $_POST["password"])) {
            $this->handleError("Informations manquantes, veuillez réessayer.");
            return;
        }

        $tokenManager = new CSRFTokenManager();

        // Vérification du jeton CSRF
        if (!isset($_POST["csrf-token"]) || !$tokenManager->validateCSRFToken($_POST["csrf-token"])) {
            $this->handleError("Jeton CSRF invalide ou expiré.");
            return;
        }

        unset($_SESSION['csrf-token'], $_SESSION['csrf-token-time']);

        $apm = new AdminProfileManager();
        $user = $apm->findByEmail($_POST["email"]);

        // Vérification de l'utilisateur et du mot de passe de manière sécurisée
        if ($user !== null && password_verify($_POST["password"], $user->getPassword())) {
            // Vérifier si le mot de passe est haché, sinon le hacher et le mettre à jour
            $passwordInfo = password_get_info($user->getPassword());
            if (!$passwordInfo['algo']) {
                $hashedPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);
                $apm->updatePassword($user->getId(), $hashedPassword);
            }

            // Connexion réussie - Stocker l'ID utilisateur et le nom dans la session
            $_SESSION["user"] = $user->getId();
            $_SESSION["user_name"] = $user->getuser_name(); // Stocker le nom d'utilisateur dans la session
            unset($_SESSION["error-message"]);

            $this->redirect("dashboard");
        } else {
            $this->handleError("Informations incorrectes, veuillez réessayer.");
        }
    }

    public function logout(): void
    {
        session_destroy();
        $this->redirect("login");
    }

    private function handleError(string $message): void
    {
        $_SESSION["error-message"] = $message;
        $this->redirect("login");
    }
}