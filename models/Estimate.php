<?php

class Estimate
{
    private ?int $id = null;

    public function __construct( private string $lastName, private string $firstName, private string $adresse, private string $city, private string $postcode, private string $phone, private string $email, private string $servicesType,private string $services, private string $paintingSurfaceType, private ?string $paintingSurfaceTypeOther,
        private string $color, private ?string $whatColor, private ?string $numberOfSurface, private string $status, private string $surfaceMaterial, private ?string $surfaceMaterialOther, private string $pvcSurfaceType, 
        private string $date, private ?string $selectedDate, private ?string $photos, private ?string $additional, private string $createdAt)
    {

    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getPostcode(): string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): void
    {
        $this->postcode = $postcode;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getServicesType(): string
    {
        return $this->servicesType;
    }

    public function setServicesType(string $servicesType): void
    {
        $this->servicesType = $servicesType;
    }

    public function getServices(): string
    {
        return $this->services;
    }

    public function setServices(string $services): void
    {
        $this->services = $services;
    }

    public function getPaintingSurfaceType(): string
    {
        return $this->paintingSurfaceType;
    }

    public function setPaintingSurfaceType(string $paintingSurfaceType): void
    {
        $this->paintingSurfaceType = $paintingSurfaceType;
    }

    public function getPaintingSurfaceTypeOther(): ?string
    {
        return $this->paintingSurfaceTypeOther;
    }

    public function setPaintingSurfaceTypeOther(?string $paintingSurfaceTypeOther): void
    {
        $this->paintingSurfaceTypeOther = $paintingSurfaceTypeOther;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    public function getWhatColor(): ?string
    {
        return $this->whatColor;
    }

    public function setWhatColor(?string $whatColor): void
    {
        $this->whatColor = $whatColor;
    }

    public function getNumberOfSurface(): ?string
    {
        return $this->numberOfSurface;
    }

    public function setNumberOfSurface(?string $numberOfSurface): void
    {
        $this->numberOfSurface = $numberOfSurface;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getSurfaceMaterial(): string
    {
        return $this->surfaceMaterial;
    }

    public function setSurfaceMaterial(string $surfaceMaterial): void
    {
        $this->surfaceMaterial = $surfaceMaterial;
    }

    public function getSurfaceMaterialOther(): ?string
    {
        return $this->surfaceMaterialOther;
    }

    public function setSurfaceMaterialOther(?string $surfaceMaterialOther): void
    {
        $this->surfaceMaterialOther = $surfaceMaterialOther;
    }

    public function getPvcSurfaceType(): string
    {
        return $this->pvcSurfaceType;
    }

    public function setPvcSurfaceType(string $pvcSurfaceType): void
    {
        $this->pvcSurfaceType = $pvcSurfaceType;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function getSelectedDate(): ?string
    {
        return $this->selectedDate;
    }

    public function setSelectedDate(?string $selectedDate): void
    {
        $this->selectedDate = $selectedDate;
    }

    public function getPhotos(): ?string
    {
        return $this->photos;
    }

    public function setPhotos(?string $photos): void
    {
        $this->photos = $photos;
    }

    public function getAdditional(): ?string
    {
        return $this->additional;
    }

    public function setAdditional(?string $additional): void
    {
        $this->additional = $additional;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}