<?php

class Devis
{
    private ?int $id = null;

    public function __construct(private string $lastName, private string $firstName, private string $adresse, private string $city, private string $postcode, private string $phone, private string $email, private string $servicesType, private string $services, private string $paintingSurfaceType,
        private ?string $paintingSurfaceTypeOther = null, private string $color, private ?string $whatColor = null, private ?string $numberOfSurface = null, private string $status, private string $surfaceMaterial, private ?string $surfaceMaterialOther = null, private string $pvcSurfaceType,
        private string $date, private ?string $selectedDate = null, private ?string $photos = null, private ?string $additional = null, private string $createdAt) 
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
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getAdresse(): string
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     */
    public function setAdresse(string $adresse): void
    {
        $this->adresse = $adresse;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getPostcode(): string
    {
        return $this->postcode;
    }

    /**
     * @param string $postcode
     */
    public function setPostcode(string $postcode): void
    {
        $this->postcode = $postcode;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
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
    public function getServicesType(): string
    {
        return $this->servicesType;
    }

    /**
     * @param string $servicesType
     */
    public function setServicesType(string $servicesType): void
    {
        $this->servicesType = $servicesType;
    }

    /**
     * @return string
     */
    public function getServices(): string
    {
        return $this->services;
    }

    /**
     * @param string $services
     */
    public function setServices(string $services): void
    {
        $this->services = $services;
    }

    /**
     * @return string
     */
    public function getPaintingSurfaceType(): string
    {
        return $this->paintingSurfaceType;
    }

    /**
     * @param string $paintingSurfaceType
     */
    public function setPaintingSurfaceType(string $paintingSurfaceType): void
    {
        $this->paintingSurfaceType = $paintingSurfaceType;
    }

    /**
     * @return string|null
     */
    public function getPaintingSurfaceTypeOther(): ?string
    {
        return $this->paintingSurfaceTypeOther;
    }

    /**
     * @param string|null $paintingSurfaceTypeOther
     */
    public function setPaintingSurfaceTypeOther(?string $paintingSurfaceTypeOther): void
    {
        $this->paintingSurfaceTypeOther = $paintingSurfaceTypeOther;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    /**
     * @return string|null
     */
    public function getWhatColor(): ?string
    {
        return $this->whatColor;
    }

    /**
     * @param string|null $whatColor
     */
    public function setWhatColor(?string $whatColor): void
    {
        $this->whatColor = $whatColor;
    }

    /**
     * @return string|null
     */
    public function getNumberOfSurface(): ?string
    {
        return $this->numberOfSurface;
    }

    /**
     * @param string|null $numberOfSurface
     */
    public function setNumberOfSurface(?string $numberOfSurface): void
    {
        $this->numberOfSurface = $numberOfSurface;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getSurfaceMaterial(): string
    {
        return $this->surfaceMaterial;
    }

    /**
     * @param string $surfaceMaterial
     */
    public function setSurfaceMaterial(string $surfaceMaterial): void
    {
        $this->surfaceMaterial = $surfaceMaterial;
    }

    /**
     * @return string|null
     */
    public function getSurfaceMaterialOther(): ?string
    {
        return $this->surfaceMaterialOther;
    }

    /**
     * @param string|null $surfaceMaterialOther
     */
    public function setSurfaceMaterialOther(?string $surfaceMaterialOther): void
    {
        $this->surfaceMaterialOther = $surfaceMaterialOther;
    }

    /**
     * @return string
     */
    public function getPvcSurfaceType(): string
    {
        return $this->pvcSurfaceType;
    }

    /**
     * @param string $pvcSurfaceType
     */
    public function setPvcSurfaceType(string $pvcSurfaceType): void
    {
        $this->pvcSurfaceType = $pvcSurfaceType;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string|null
     */
    public function getSelectedDate(): ?string
    {
        return $this->selectedDate;
    }

    /**
     * @param string|null $selectedDate
     */
    public function setSelectedDate(?string $selectedDate): void
    {
        $this->selectedDate = $selectedDate;
    }

    /**
     * @return string|null
     */
    public function getPhotos(): ?string
    {
        return $this->photos;
    }

    /**
     * @param string|null $photos
     */
    public function setPhotos(?string $photos): void
    {
        $this->photos = $photos;
    }
}