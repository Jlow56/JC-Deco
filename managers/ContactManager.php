<?php

class ContactManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll(): array
    {
        $query = $this->db->prepare('SELECT * FROM contact_form');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $contacts = [];

        foreach ($result as $item) {
            $contact = new Contact(
                firstName: $item["first_name"],
                lastName: $item["last_name"],
                phoneNumber: $item["phone_number"],
                email: $item["email"],
                city: $item["city"],
                zipCode: $item["zip_code"],
                message: $item["message"],
                createdAt: $item["created_at"]  
            );
            $contact->setId($item["id"]);
            $contact->setCreatedAt($item["created_at"]);
            $contacts[] = $contact;
        }
        return $contacts;
    }

    public function getContactById(int $id): ?Contact
    {
        $query = $this->db->prepare('SELECT * FROM contact_form WHERE id=:id');
        $query->execute(['id' => $id]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $contact = new Contact(
                firstName: $result["first_name"],
                lastName: $result["last_name"],
                phoneNumber: $result["phone_number"],
                email: $result["email"],
                city: $result["city"],
                zipCode: $result["zip_code"],
                message: $result["message"],
                createdAt: $result["created_at"] 
            );
            $contact->setId($result["id"]);
            $contact->setCreatedAt($result["created_at"]);
            return $contact;
        }
        return null;
    }

    public function createContact(Contact $contact): void
    {
        $query = $this->db->prepare('INSERT INTO contact_form (first_name, last_name, phone_number, email, city, zip_code,
                message, created_at) VALUES (:first_name, :last_name, :phone_number, :email, :city, :zip_code, :message, :created_at)');
        $parameters = [
            'first_name' => $contact->getFirstName(),
            'last_name' => $contact->getLastName(),
            'phone_number' => $contact->getPhoneNumber(),
            'email' => $contact->getEmail(),
            'city' => $contact->getCity(),
            'zip_code' => $contact->getZipCode(),
            'message' => $contact->getMessage(),
            'created_at' => date('Y-m-d H:i:s') 
        ];
        $query->execute($parameters);
        $contact->setId($this->db->lastInsertId());
    }

    public function updateContact(Contact $contact): void
    {
        $query = $this->db->prepare('UPDATE contact_form SET first_name=:first_name, last_name=:last_name, phone_number=:phone_number,
                email=:email, city=:city, zip_code=:zip_code, message=:message WHERE id=:id');
        $parameters = [
            'first_name' => $contact->getFirstName(),
            'last_name' => $contact->getLastName(),
            'phone_number' => $contact->getPhoneNumber(),
            'email' => $contact->getEmail(),
            'city' => $contact->getCity(),
            'zip_code' => $contact->getZipCode(),
            'message' => $contact->getMessage(),
            'id' => $contact->getId()
        ];
        $query->execute($parameters);
    }

    public function deleteContact(int $id): void
    {
        $query = $this->db->prepare('DELETE FROM contact_form WHERE id=:id');
        $query->execute(params:['id' => $id]);
    }
}