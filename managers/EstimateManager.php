<?php
class EstimateManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll(): array
    {
        $query = $this->db->prepare('SELECT * FROM devis_form');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $estimates = [];

        foreach ($result as $item) {
            $estimate = new Estimate(
                $item["last_name"],
                $item["first_name"],
                $item["adresse"],
                $item["city"],
                $item["postcode"],
                $item["phone"],
                $item["email"],
                $item["services_type"],
                $item["services"],
                $item["painting_surface_type"],
                $item["painting_surface_type_other"] ?? null,
                $item["color"],
                $item["what_color"] ?? null,
                $item["surface_count"] ?? null,
                $item["status"],
                $item["surface_material"],
                $item["surface_material_other"] ?? null,
                $item["pvc_surface_type"],
                $item["date"],
                $item["selected_date"] ?? null,
                $item["picture"] ?? null,
                $item["additional"] ?? null,
                $item["created_at"]
            );
            $estimate->setId($item["id"]);
            $estimates[] = $estimate;
        }

        return $estimates;
    }

    public function getEstimateById(int $id): ?Estimate
    {
        $query = $this->db->prepare('SELECT * FROM devis_form WHERE id = :id');
        $query->execute(["id" => $id]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $estimate = new Estimate(
                $result['last_name'],
                $result['first_name'],
                $result['adresse'],
                $result['city'],
                $result['postcode'],
                $result['phone'],
                $result['email'],
                $result['services_type'],
                $result['services'],
                $result['painting_surface_type'],
                $result['painting_surface_type_other'] ?? null,
                $result['color'],
                $result['what_color'] ?? null,
                $result['surface_count'] ?? null,
                $result['status'],
                $result['surface_material'],
                $result['surface_material_other'] ?? null,
                $result['pvc_surface_type'],
                $result['date'],
                $result['selected_date'] ?? null,
                $result['picture'] ?? null,
                $result['additional'] ?? null,
                $result['created_at']
            );
            $estimate->setId($result["id"]);
            return $estimate;
        }
        return null;
    }

    public function createEstimate(Estimate $estimate): void
    {
        $query = $this->db->prepare('INSERT INTO devis_form (last_name, first_name, adresse, city, postcode, phone, email, services_type, services, painting_surface_type, painting_surface_type_other, color, what_color, surface_count, status, surface_material, surface_material_other, pvc_surface_type, date, selected_date, picture, additional, created_at) 
            VALUES (:last_name, :first_name, :adresse, :city, :postcode, :phone, :email, :services_type, :services, :painting_surface_type, :painting_surface_type_other, :color, :what_color, :surface_count, :status, :surface_material, :surface_material_other, :pvc_surface_type, :date, :selected_date, :picture, :additional, :created_at)
        ');

        $parameters =
            [
                "last_name" => $estimate->getLastName(),
                "first_name" => $estimate->getFirstName(),
                "adresse" => $estimate->getAdresse(),
                "city" => $estimate->getCity(),
                "postcode" => $estimate->getPostcode(),
                "phone" => $estimate->getPhone(),
                "email" => $estimate->getEmail(),
                "services_type" => $estimate->getServicesType(),
                "services" => $estimate->getServices(),
                "painting_surface_type" => $estimate->getPaintingSurfaceType(),
                "painting_surface_type_other" => $estimate->getPaintingSurfaceTypeOther() ?? null,
                "color" => $estimate->getColor(),
                "what_color" => $estimate->getWhatColor() ?? null,
                "surface_count" => $estimate->getSurfaceCount() ?? null,
                "status" => $estimate->getStatus(),
                "surface_material" => $estimate->getSurfaceMaterial(),
                "surface_material_other" => $estimate->getSurfaceMaterialOther() ?? null,
                "pvc_surface_type" => $estimate->getPvcSurfaceType(),
                "date" => $estimate->getDate(),
                "selected_date" => $estimate->getSelectedDate() ?? null,
                "picture" => $estimate->getPicture() ?? null,
                "additional" => $estimate->getAdditional() ?? null,
                "created_at" => $estimate->getCreatedAt()
            ];

        $query->execute($parameters);
        $estimate->setId($this->db->lastInsertId());
    }

    public function updateEstimate(Estimate $estimate): void
    {
        $query = $this->db->prepare('UPDATE devis_form SET last_name = :last_name, first_name = :first_name, adresse = :adresse, city = :city, postcode = :postcode, phone = :phone, email = :email, services_type = :services_type, services = :services, painting_surface_type = :painting_surface_type,
                painting_surface_type_other = :painting_surface_type_other, color = :color, what_color = :what_color, surface_count = :surface_count, status = :status, surface_material = :surface_material, surface_material_other = :surface_material_other, 
                pvc_surface_type = :pvc_surface_type, date = :date, selected_date = :selected_date, picture = :picture, additional = :additional, created_at = :created_at WHERE id = :id');
        $parameters = [
            "id" => $estimate->getId(),
            "last_name" => $estimate->getLastName(),
            "first_name" => $estimate->getFirstName(),
            "adresse" => $estimate->getAdresse(),
            "city" => $estimate->getCity(),
            "postcode" => $estimate->getPostcode(),
            "phone" => $estimate->getPhone(),
            "email" => $estimate->getEmail(),
            "services_type" => $estimate->getServicesType(),
            "services" => $estimate->getServices(),
            "painting_surface_type" => $estimate->getPaintingSurfaceType(),
            "painting_surface_type_other" => $estimate->getPaintingSurfaceTypeOther() ?? null,
            "color" => $estimate->getColor(),
            "what_color" => $estimate->getWhatColor() ?? null,
            "surface_count" => $estimate->getSurfaceCount() ?? null,
            "status" => $estimate->getStatus(),
            "surface_material" => $estimate->getSurfaceMaterial(),
            "surface_material_other" => $estimate->getSurfaceMaterialOther() ?? null,
            "pvc_surface_type" => $estimate->getPvcSurfaceType(),
            "date" => $estimate->getDate(),
            "selected_date" => $estimate->getSelectedDate() ?? null,
            "picture" => $estimate->getPicture() ?? null,
            "additional" => $estimate->getAdditional() ?? null,
            "created_at" => $estimate->getCreatedAt()
        ];
        $query->execute($parameters);
    }

    public function deleteEstimate(int $id): void
    {
        $query = $this->db->prepare('DELETE FROM devis_form WHERE id = :id');
        $query->execute(["id" => $id]);
    }
}