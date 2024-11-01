<?php
class ServiceManager extends AbstractManager
{
    public function findAllIfVisible(): array
    {
        $smm = new ServiceMediaManager();

        $query = $this->db->prepare('SELECT * FROM service WHERE visible = 1');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $services = [];

        foreach ($result as $item) {
            $medias = $smm->findVisibleService($item["id"]);
            $service = new Service($item["title1"], $item["title2"], $item["title3"], $item["content"], $item["visible"]);

            $service->setId($item["id"]);
            $service->setMedia($medias ?? []);

            $services[] = $service;
        }
        return $services;
    }

    /*************************************/
    //**         Admin tools*        **//

    // Used for Admin-List
    public function findAll(): array
    {
        $smm = new ServiceMediaManager();

        $query = $this->db->prepare('SELECT * FROM service');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $services = [];

        foreach ($result as $item) {
            $medias = $smm->findByServiceId($item["id"]);
            $service = new Service($item["title1"], $item["title2"], $item["title3"], $item["content"], $item["visible"]);

            $service->setId($item["id"]);
            $service->setMedia($medias ?? []);

            $services[] = $service;
        }
        return $services;
    }

    public function getServiceById(int $id): Service
    {
        $smm = new ServiceMediaManager();

        $query = $this->db->prepare('SELECT * FROM service WHERE id = :id');
        $query->execute(['id' => $id]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new RuntimeException('Service introuvable');
        }

        $medias = $smm->findByServiceId($result["id"]);
        $service = new Service($result["title1"], $result["title2"], $result["title3"], $result["content"], $result["visible"]);

        $service->setId($result["id"]);
        $service->setMedia($medias ?? []);

        return $service;
    }

    public function findLatest(): array
    {
        $smm = new ServiceMediaManager();
        $query = $this->db->prepare('SELECT * FROM service WHERE visible = 1 LIMIT 1 ');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $services = [];

        foreach ($result as $item) {
            $medias = $smm->findVisibleService($item["id"]);
            $service = new Service($item["title1"], $item["title2"], $item["title3"], $item["content"], $item["visible"]);

            $service->setId($item["id"]);
            $service->setMedia($medias ?? []);

            $services[] = $service;
        }
        return $services;
    }

    public function createService(Service $service, array $mediaIds): void
    {
        $query = $this->db->prepare('INSERT INTO service (title1, title2, title3, content, visible) VALUES (:title1, :title2, :title3, :content, :visible)');
        $parameters = [
            "title1" => $service->getTitle1(),
            "title2" => $service->getTitle2(),
            "title3" => $service->getTitle3(),
            "content" => $service->getContent(),
            "visible" => $service->getVisible(),
        ];

        try {
            $query->execute($parameters);
            $service->setId($this->db->lastInsertId());

            // Associer les médias au service
            foreach ($mediaIds as $mediaId) {
                $mm = new MediaManager();
                $smm = new ServiceMediaManager();
                $mm->createMedia($mediaId);
                $smm->associateMediaWithService($service->getId(), $mediaId);
            }
        } catch (PDOException $e) {
            throw new RuntimeException('Erreur lors de la création du service : ' . $e->getMessage());
        }
    }

    public function updateService(Service $service, array $mediaIds): void
    {
        $query = $this->db->prepare('UPDATE service SET title1 = :title1, title2 = :title2, title3 = :title3, content = :content, visible = :visible WHERE id = :id');
        $parameters = [
            "id" => $service->getId(),
            "title1" => $service->getTitle1(),
            "title2" => $service->getTitle2(),
            "title3" => $service->getTitle3(),
            "content" => $service->getContent(),
            "visible" => $service->getVisible(),
        ];

        try {
            $query->execute($parameters);

            // Mettre à jour les associations de médias
            $smm = new ServiceMediaManager();
            $smm->updateServiceMediaAssociation($service->getId(), $mediaIds);
        } catch (PDOException $e) {
            throw new RuntimeException('Erreur lors de la mise à jour du service : ' . $e->getMessage());
        }
    }

    public function deleteService(int $id): void
    {
        try {
            // Delete from service_media
            $query = $this->db->prepare('DELETE FROM service_media WHERE service_id = :id');
            $query->execute(['id' => $id]);

            // Delete the service itself
            $query = $this->db->prepare('DELETE FROM service WHERE id = :id');
            $parameters = ["id" => $id];
            $query->execute($parameters);
        } catch (PDOException $e) {
            throw new RuntimeException('Erreur lors de la suppression du service : ' . $e->getMessage());
        }
    }

}