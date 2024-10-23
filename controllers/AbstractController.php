<?php

require 'vendor/autoload.php';

// https://github.com/PHPMailer/PHPMailer/blob/master/README.md
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
abstract class AbstractController
{
    private \Twig\Environment $twig;
    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment(
            $loader,
            [
                'debug' => true,
                // répertoire de cache de compilation, où Twig met en cache les modèles compilés pour éviter la phase d'analyse des requêtes suivantes.
                // 'cache' => 'compilation_cache',
            ]
        );

        $twig->addExtension(new \Twig\Extension\DebugExtension());
        // ajout du token
        $twig->addGlobal('sessionToken', $_SESSION["csrf-token"]);

        $twig->addGlobal('url', $_SERVER['REQUEST_URI']);
        $uri = $_SERVER['REQUEST_URI'];
        $segments = explode('/', $uri);
        $route = end($segments);
        $twig->addGlobal('current_route', $route);
        $this->twig = $twig;
    }

    protected function render(string $template, array $data): void
    {
        echo $this->twig->render($template, $data);
    }

    protected function redirect(string $route): void
    {
        header("Location: index.php?route=$route");
        exit();
    }

    // Cette fonction sert à convertir un tableau PHP en une chaîne JSON et à l'afficher.
    protected function renderJson(array $data): void
    {
        echo json_encode($data);
    }

    protected function newEstimate(): bool
    {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            // Set SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp-mail.outlook.com';
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV["SMTP_MAIL"];
            $mail->Password = $_ENV["SMTP_PASSWORD"];
            $mail->CharSet = "utf-8";
            $mail->setFrom($_ENV["SMTP_MAIL"], 'Site JC-Déco');
            $mail->addAddress($_ENV["SMTP_MAIL"], 'Admin');
            $mail->isHTML(true);
            $mail->Subject = "Demande de devis via le site -JC-Déco";
            $mail->Body = 'Nouveau devis via votre site internet. 
                <a href="">
                    Cliquez ici pour consulter la demande.
                </a>';
            $mail->AltBody = 'Cliquez sur ce lien pour consulter votre nouvelle demande de contact : https:// /index.php?route='; // raw text
            $mail->send();
            return true;
        } catch (Exception) {
            return false;
        }
    }

    protected function newContact(): bool
    {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            // Set SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp-mail.outlook.com';
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV["SMTP_MAIL"];
            $mail->Password = $_ENV["SMTP_PASSWORD"];
            $mail->CharSet = "utf-8";
            $mail->setFrom($_ENV["SMTP_MAIL"], 'Site JC-Déco');
            $mail->addAddress($_ENV["SMTP_MAIL"], 'Admin');
            $mail->isHTML(true);
            $mail->Subject = "Demande de contact via le site -JC-Déco";
            $mail->Body = 'Nouveau message concernant une demande contact via votre site internet. 
                <a href="">
                    Cliquez ici pour consulter la demande.
                </a>';
            $mail->AltBody = 'Cliquez sur ce lien pour consulter votre nouvelle demande de contact : https:// /index.php?route='; // raw text
            $mail->send();
            return true;
        } catch (Exception) {
            return false;
        }
    }
}

