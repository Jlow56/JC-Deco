<?php

class AuthController extends AbstractController
{
    public function login(): void
    {
        $this->render("admin/login.html.twig", []);
    }

    public function loginCheck(): void
    {
        if (isset($_POST["email"]) && isset($_POST["password"])) {
            $tokenManager = new CSRFTokenManager();

            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                $apm = new AdminProfileManager();
                $user = $apm->findByEmail($_POST["email"]);

                if ($user !== null) {
                    if (password_verify($_POST["password"], $user->getPassword())) {
                        $_SESSION["user"] = $user->getId();

                        unset($_SESSION["error-message"]);

                        $this->redirect("dashboard");
                    } else {
                        $_SESSION["error-message"] = "Mot de passe erroné, veuillez réessayer.";
                        $this->redirect("login");
                    }
                } else {
                    $_SESSION["error-message"] = "Informations erronées, veuillez réessayer.";
                    $this->redirect("login");
                }
            } else {
                $_SESSION["error-message"] = "Invalid CSRF token";
                $this->redirect("login");
            }
        } else {
            $_SESSION["error-message"] = "Informations manquantes, veuillez réessayer.";
            $this->redirect("login");
        }
    }

    public function logout() : void
    {
        session_destroy();

        $this->redirect("login");
    }
}
