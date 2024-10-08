<?php

class AdminProfile
{
    private ?int $id = null;

    public function __construct(private string $user_name, private string $email, private string $password, private DateTime $created_at)
    {

    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getuser_name(): string
    {
        return $this->user_name;
    }
    /**
     * @param string $user_name
     */
    public function setuser_name(string $user_name): void
    {
        $this->user_name = $user_name;
    }
    
    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    /**
     * @param string $email
     */
    public function setEmail(string $email): void    
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return DateTime
     */
    public function getCreated_at(): DateTime
    {
        return $this->created_at;
    }
    /**
     * @param DateTime $created_at
     */
    public function setCreated_at(DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }
}