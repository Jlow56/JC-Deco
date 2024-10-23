<?php
session_start();

require "vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Génération du token CSRF
if (!isset($_SESSION["csrf-token"])) {
    $tokenManager = new CSRFTokenManager();
    $token = $tokenManager->generateCSRFToken();
    $_SESSION["csrf-token"] = $token;
}

// Gestion des requêtes via le routeur
$router = new Router();
$router->handleRequest($_GET);
