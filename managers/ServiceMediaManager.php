<?php
class ServiceMediaManager extends AbstractManager
{
    //****************************//
    //**      Service Media     **//            
    //****************************//
    public function findVisibleService(int $serviceId): array
    {
        $query = $this->db->prepare('SELECT media.* FROM media JOIN service_media ON media.id = service_media.media_id WHERE service_media.service_id = :service_id AND media.visible = 1');
        $parameters = ["service_id" => $serviceId];
        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $medias = [];

        if (!empty($result)) {
            foreach ($result as $item) {
                $media = new Media($item["url"], $item["alt"], $item["visible"]);
                $media->setId($item["id"]);
                $medias[] = $media;
            }
        }
        return $medias;
    }

    /*******************************************/
    //**      Service Media ADMIN  **// 

    public function findByServiceId(int $serviceId): ?array
    {
        $query = $this->db->prepare('SELECT media.* FROM media JOIN service_media ON media.id = service_media.media_id WHERE service_media.service_id = :service_id');
        $parameters = ["service_id" => $serviceId];
        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $medias = [];

        if (!empty($result)) {
            foreach ($result as $item) {
                $media = new Media($item["url"], $item["alt"], $item["visible"]);
                $media->setId($item["id"]);
                $medias[] = $media;
            }
        }
        return $medias;
    }

    public function associateMediaWithService(int $serviceId, int $mediaId): void
    {
        $query = $this->db->prepare('INSERT INTO service_media (service_id, media_id) VALUES (:service_id, :media_id)');
        $parameters = [
            "service_id" => $serviceId,
            "media_id" => $mediaId,
        ];

        try {
            $query->execute($parameters);
        } catch (PDOException $e) {
            throw new RuntimeException('Erreur lors de l\'association du média au service : ' . $e->getMessage());
        }
    }


    public function updateServiceMediaAssociation(int $serviceId, array $mediaIds): void
    {
        $this->db->beginTransaction();

        try {
            $updateQuery = $this->db->prepare('UPDATE service_media SET media_id = :new_media_id WHERE service_id = :service_id AND media_id = :old_media_id');

            foreach ($mediaIds as $oldMediaId => $newMediaId) 
            {
                $parameters = [
                    "new_media_id" => $newMediaId,
                    "service_id" => $serviceId,
                    "old_media_id" => $oldMediaId,
                ];
                $updateQuery->execute($parameters);
            }

            $this->db->commit();
        } 
        catch (PDOException $e) 
        {
            $this->db->rollBack();
            throw new RuntimeException('Erreur lors de la mise à jour des associations médias pour le service : ' . $e->getMessage());
        }
    }


    public function DeleteServiceMedia(int $serviceId, array $mediaIds): void
    {
        $this->db->beginTransaction();
        try {
            $query = $this->db->prepare('DELETE FROM service_media WHERE service_id = :service_id');
            $query->execute(['service_id' => $serviceId]);

            $deleteQuery = $this->db->prepare('DELETE FROM media WHERE id = :id');
            foreach ($mediaIds as $mediaId) {
                $deleteQuery->execute(['id' => $mediaId]);
            }
            $this->db->commit();
        } 
        catch (PDOException $e) 
        {
            $this->db->rollBack();
            throw new RuntimeException('Erreur lors de la suppression des associations médias pour le service : ' . $e->getMessage());
        }
    }
}