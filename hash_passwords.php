<?php
session_start();
require "vendor/autoload.php"; // Assurez-vous de charger vos dépendances

$apm = new AdminProfileManager();
$users = $apm->findAllUsers(); // Méthode pour récupérer tous les utilisateurs

foreach ($users as $user) {
    $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
    $apm->updatePassword($user->getId(), $hashedPassword);
}

echo "Mots de passe mis à jour avec succès.";