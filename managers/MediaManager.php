<?php
class MediaManager extends AbstractManager
{
    //****************************//
    //**         Général        **//            
    //****************************//

    public function findAllMedia(): ?array
    {
        $query = $this->db->prepare('SELECT * FROM media');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $medias = [];

        if (!empty($result)) {
            foreach ($result as $item) 
            {
                $media = new Media($item["url"], $item["alt"], $item["visible"]);
                $media->setId($item["id"]);
                $medias[] = $media;
            }
        }
        return $medias;
    }

    public function createMedia(Media $media): void
    {
        $query = $this->db->prepare('INSERT INTO media (id, url, alt, visible) VALUES (NULL, :url, :alt, :visible)');
        $parameters = [
            "url" => $media->getUrl(),
            "alt" => $media->getAlt(),
            "visible" => $media->isVisible(),
        ];

        try {
            $query->execute($parameters);
            $media->setId($this->db->lastInsertId());
        } catch (PDOException $e) {
            // Gestion d'erreur
            throw new RuntimeException('Erreur lors de la création du media : ' . $e->getMessage());
        }
    }

    public function deleteMedia(int $id): void
    {
        $query = $this->db->prepare('DELETE FROM media WHERE id = :id');
        $query->execute(['id' => $id]);
    }

    //****************************//
    //**        Home Media      **//            
    //****************************//
    public function findByHome(int $homeId): ?array
    {
        $query = $this->db->prepare('SELECT media.* FROM media JOIN home_media ON media.id = home_media.media_id WHERE home_media.home_id = :home_id');
        $parameters = ["home_id" => $homeId];
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

    //****************************//
    //**      Service Media     **//            
    //****************************//
    public function findVisibleService(int $serviceId): array
    {
        $query = $this->db->prepare('SELECT media.* FROM media JOIN service_media ON media.id = service_media.media_id WHERE service_media.service_id = :service_id AND media.visible = 1');
        $parameters = ["service_id" => $serviceId];
        $query->execute($parameters);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /*******************************************/
    //**      Service Media ADMIN  **// 

    public function findByService(int $serviceId): ?array
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
    
    public function updateMediaAssociationsForService(int $serviceId, array $mediaIds): void
    {
        try {
            // Démarrer une transaction
            $this->db->beginTransaction();

            // Récupérer les associations actuelles
            $query = $this->db->prepare('SELECT media_id FROM service_media WHERE service_id = :service_id');
            $query->execute(['service_id' => $serviceId]);
            $currentAssociations = $query->fetchAll(PDO::FETCH_COLUMN);

            // Convertir les résultats en tableau d'int
            $currentAssociations = array_map('intval', $currentAssociations);

            // Trouver les associations à ajouter
            $mediaIdsToAdd = array_diff($mediaIds, $currentAssociations);

            // Trouver les associations à supprimer
            $mediaIdsToDelete = array_diff($currentAssociations, $mediaIds);

            // Ajouter les nouvelles associations
            if (!empty($mediaIdsToAdd)) {
                $insertQuery = $this->db->prepare('INSERT INTO service_media (service_id, media_id) VALUES (:service_id, :media_id)');
                foreach ($mediaIdsToAdd as $mediaId) {
                    $insertQuery->execute(['service_id' => $serviceId, 'media_id' => $mediaId]);
                }
            }

            // Supprimer les associations obsolètes
            if (!empty($mediaIdsToDelete)) {
                $deleteQuery = $this->db->prepare('DELETE FROM service_media WHERE service_id = :service_id AND media_id = :media_id');
                foreach ($mediaIdsToDelete as $mediaId) {
                    $deleteQuery->execute(['service_id' => $serviceId, 'media_id' => $mediaId]);
                }
            }

            // Valider la transaction
            $this->db->commit();
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $this->db->rollBack();
            throw new RuntimeException('Erreur lors de la mise à jour des associations médias pour le service : ' . $e->getMessage());
        }
    }

    public function DeleteServiceMedia(int $serviceId, array $mediaIds): void
    {
        $query = $this->db->prepare('DELETE FROM service_media WHERE service_id = :service_id');
        $query->execute(['service_id' => $serviceId]);

        foreach ($mediaIds as $mediaId) {
            $this->associateMediaWithService($serviceId, $mediaId);
        }
    }

   
    //****************************//
    //**    Realisation Media   **//            
    //****************************//

    // Use by findAll in RealisationManager
    public function findVisibleRealisation(int $realisationId): array
    {
        $query = $this->db->prepare('SELECT media.* FROM media JOIN realisation_media ON media.id = realisation_media.media_id WHERE realisation_media.realisation_id = :realisation_id AND media.visible = 1');
        $parameters = ["realisation_id" => $realisationId];
        $query->execute(params: $parameters);
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
    //**      Realisation Media ADMIN  **//            

    // Use find All for admin-realisation
    public function findByRealisation(int $realisationId): ?array
    {
        $query = $this->db->prepare('SELECT media.* FROM media JOIN realisation_media ON media.id = realisation_media.media_id WHERE realisation_media.realisation_id = :realisation_id');
        $parameters = ["realisation_id" => $realisationId];
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

    // Méthode pour associer un média à une réalisation
    public function associateMediaWithRealisation(int $realisationId, int $mediaId): void
    {
        $query = $this->db->prepare('INSERT INTO realisation_media (realisation_id, media_id) VALUES (:realisation_id, :media_id)');
        $parameters = [
            "realisation_id" => $realisationId,
            "media_id" => $mediaId,
        ];

        try {
            // Exécution de l'insertion dans la table d'association
            $query->execute($parameters);
        } catch (PDOException $e) {
            // Gestion d'erreur pour l'association
            throw new RuntimeException('Erreur lors de l\'association du média à la réalisation : ' . $e->getMessage());
        }
    }

    public function updateMediaAssociationsForRealisation(int $realisationId, array $mediaIds): void
    {
        $query = $this->db->prepare('DELETE FROM realisation_media WHERE realisation_id = :realisation_id');
        $query->execute(['realisation_id' => $realisationId]);

        foreach ($mediaIds as $mediaId) {
            $this->associateMediaWithRealisation($realisationId, $mediaId);
        }
    }
    // Update media associations for a realisation
    public function DeleteRealisationMedia(int $realId, array $mediaIds): void
    {
        // Remove all existing associations
        $query = $this->db->prepare('DELETE FROM realisation_media WHERE realisation_id = :media_id');
        $query->execute(['realId' => $realId]);

        // Add the new associations
        foreach ($mediaIds as $mediaId) {
            $this->associateMediaWithRealisation($realId, $mediaId);
        }
    }

}