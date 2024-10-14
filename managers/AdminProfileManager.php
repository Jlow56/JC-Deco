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

        $parameters =
            [
                "email" => $email
            ];

        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result)
        {
            $user = new AdminProfile($result["user_name"], $result["email"], $result["password"], $result["created_at"]);
            $user->setId($result["id"]);
            
            return $user;
        }
        return null;
    }

    public function findOne(int $id): ?AdminProfile
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE id=:id');

        $parameters = 
        [
            "id" => $id
        ];

        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result)
        {
            $user = new AdminProfile($result["user_name"], $result["email"], $result["password"], $result["created_at"]);
            $user->setId($result["id"]);

            return $user;
        }
        return null;
    }

    public function insert(AdminProfile $user): int
    {
        $query = $this->db->prepare('INSERT INTO admin_profile (user_name, email, password, created_at) VALUES (:user_name, :email, :password, :created_at)');

        $parameters =
        [
            "user_name" => $user->getuser_name(),
            "email" => $user->getEmail(),
            "password" => $user->getPassword(),
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
}