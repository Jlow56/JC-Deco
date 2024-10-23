<?php
class RealisationMediaManager extends AbstractManager
{
    //****************************//
    //**    Realisation Media   **//            
    //****************************//
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

    public function findByRealisationId(int $realisationId): ?array
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

    public function associateMediaWithRealisation(int $realisationId, int $mediaId): void
    {
        $query = $this->db->prepare('INSERT INTO realisation_media (realisation_id, media_id) VALUES (:realisation_id, :media_id)');
        $parameters =
            [
                "realisation_id" => $realisationId,
                "media_id" => $mediaId,
            ];

        try {
            $query->execute($parameters);
        } catch (PDOException $e) {
            throw new RuntimeException('Erreur lors de l\'association du média à la réalisation : ' . $e->getMessage());
        }
    }

    public function updateRealisationMediaAssociation(int $realisationId, int $oldMediaId, int $newMediaId): void
    {
        $this->db->beginTransaction();

        try {
            $query = $this->db->prepare('UPDATE realisation_media SET media_id = :new_media_id WHERE realisation_id = :realisation_id AND media_id = :old_media_id');

            $parameters = [
                "new_media_id" => $newMediaId,
                "realisation_id" => $realisationId,
                "old_media_id" => $oldMediaId,
            ];
            $query->execute($parameters);
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new RuntimeException('Erreur lors de la mise à jour de l\'association du média à la réalisation : ' . $e->getMessage());
        }
    }

    public function deleteRealisationMedia(int $realisationId, array $mediaIds): void
    {
        $this->db->beginTransaction(); 

        try {
            $query = $this->db->prepare('DELETE FROM realisation_media WHERE realisation_id = :realisation_id');
            $query->execute(['realisation_id' => $realisationId]);

            $deleteQuery = $this->db->prepare('DELETE FROM media WHERE id = :id');
            foreach ($mediaIds as $mediaId) {
                $deleteQuery->execute(['id' => $mediaId]);
            }

            $this->db->commit(); 
        } 
        catch (PDOException $e)
        {
            $this->db->rollBack(); 
            throw new RuntimeException('Erreur lors de la suppression des médias de la réalisation : ' . $e->getMessage());
        }
    }
}