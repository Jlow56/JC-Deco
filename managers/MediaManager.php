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
            foreach ($result as $item) {
                $media = new Media($item["url"], $item["alt"], $item["visible"]);
                $media->setId($item["id"]);
                $medias[] = $media;
            }
        }
        return $medias;
    }

    public function findVisibleMedia(): ?array
    {
        $query = $this->db->prepare('SELECT * FROM media WHERE visible = 1');
        $query->execute();
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

    public function findMediaById(int $id): ?Media
    {
        $query = $this->db->prepare('SELECT * FROM media WHERE id = :id');
        $query->execute(['id' => $id]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        $media = new Media($result["url"], $result["alt"], $result["visible"]);
        $media->setId($result["id"]);
        return $media;
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
    
    public function updateMedia(Media $media): void
    {
        $query = $this->db->prepare('UPDATE media SET url = :url, alt = :alt, visible = :visible WHERE id = :id');
        $parameters = [
            "id" => $media->getId(),
            "url" => $media->getUrl(),
            "alt" => $media->getAlt(),
            "visible" => $media->isVisible(),
        ];

        try {
            $query->execute($parameters);
        } catch (PDOException $e) {
            throw new RuntimeException('Erreur lors de la mise à jour du media : ' . $e->getMessage());
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
}