<?php

class AdminProfileManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findByEmail(string $email): ?AdminProfile
    {
        $query = $this->db->prepare('SELECT * FROM admin_profile WHERE email=:email');

        $parameters = ["email" => $email];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $createdAt = new DateTime($result["created_at"]);
            $user = new AdminProfile($result["user_name"], $result["email"], $result["password"], $createdAt);
            $user->setId($result["id"]);

            return $user;
        }
        return null;
    }

    public function findOne(int $id): ?AdminProfile
    {
        $query = $this->db->prepare('SELECT * FROM admin_profile WHERE id=:id');

        $parameters = ["id" => $id];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $createdAt = new DateTime($result["created_at"]); // Convert string to DateTime
            $user = new AdminProfile($result["user_name"], $result["email"], $result["password"], $createdAt);
            $user->setId($result["id"]);

            return $user;
        }
        return null;
    }

    public function findAllUsers(): array
    {
        $query = $this->db->query('SELECT * FROM admin_profile');
        $users = $query->fetchAll(PDO::FETCH_ASSOC);
        $results = [];

        foreach ($users as $user) {
            $createdAt = new DateTime($user["created_at"]);
            $result = new AdminProfile($user["user_name"], $user["email"], $user["password"], $createdAt);
            $result->setId($user["id"]);
            $results[] = $result;
        }

        return $results;
    }

    public function createAdmin(AdminProfile $user): int
    {
        // Validation de l'email
        if (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'email n'est pas valide.");
        }

        // Hacher le mot de passe avant de l'insérer
        $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        if ($hashedPassword === false) {
            throw new Exception("Erreur lors du hachage du mot de passe.");
        }

        // Insérer l'utilisateur
        $query = $this->db->prepare('INSERT INTO admin_profile (user_name, email, password, created_at) VALUES (:user_name, :email, :password, :created_at)');

        $parameters = [
            "user_name" => $user->getuser_name(),
            "email" => $user->getEmail(),
            "password" => $hashedPassword, // Utiliser le mot de passe haché
            "created_at" => $user->getCreated_at()->format('Y-m-d H:i:s')
        ];
        $query->execute($parameters);

        return (int) $this->db->lastInsertId();
    }
    public function update(AdminProfile $user): void
    {
        $query = $this->db->prepare('UPDATE admin_profile SET user_name=:user_name, email=:email, password=:password, created_at=:created_at WHERE id=:id');

        $parameters =
            [
                "id" => $user->getId(),
                "user_name" => $user->getuser_name(),
                "email" => $user->getEmail(),
                "password" => $user->getPassword(),
                "created_at" => $user->getCreated_at()->format('Y-m-d H:i:s')
            ];
        $query->execute($parameters);
    }

    public function updatePassword(int $id, string $hashedPassword): void
    {
        $query = $this->db->prepare('UPDATE admin_profile SET password = :password WHERE id = :id');
        $parameters = ['password' => $hashedPassword, 'id' => $id];
        $query->execute($parameters);
    }

    public function deleteAdmin(int $id): void
    {

        $query = $this->db->prepare('DELETE FROM admin_profile WHERE id=:id');

        $parameters =
            [
                "id" => $id
            ];
        $query->execute($parameters);
    }
}