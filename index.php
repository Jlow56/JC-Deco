<?php
session_start();

require "vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (!isset($_SESSION["csrf-token"])) 
{
    $tokenManager = new CSRFTokenManager();
    $token = $tokenManager->generateCSRFToken();
    $_SESSION["csrf-token"] = $token;
}

$router = new Router();
$router->handleRequest($_GET);
