<?php

class RealisationManager extends AbstractManager
{

    public function findAllIfVisible(): array
    {
        $rmm = new RealisationMediaManager();

        $query = $this->db->prepare('SELECT * FROM realisation WHERE visible = 1');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $realisations = [];

        foreach ($result as $item) {
            $medias = $rmm->findVisibleRealisation($item['id']);
            $realisation = new Realisation($item["title1"], $item["title2"], $item["title3"], $item["content"], $item["visible"]);

            $realisation->setId($item["id"]);
            $realisation->setMedia($medias ?? []);

            $realisations[] = $realisation;
        }
        return $realisations;
    }

    /*************************************/
    //**         Admin tools*        **//

    // Used for list-realisation
    public function findAll(): array
    {
        $rmm = new RealisationMediaManager();

        $query = $this->db->prepare('SELECT * FROM realisation');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $realisations = [];

        foreach ($result as $item) {
            $medias = $rmm->findByRealisationId($item['id']);
            $realisation = new Realisation($item["title1"], $item["title2"], $item["title3"], $item["content"], $item["visible"]);

            $realisation->setId($item["id"]);
            $realisation->setMedia($medias ?? []);

            $realisations[] = $realisation;
        }
        return $realisations;
    }

    // Used for show-realisation and create-realisation
    public function getRealisationById(int $id): Realisation
    {
        $rmm = new RealisationMediaManager();

        $query = $this->db->prepare('SELECT * FROM realisation WHERE id = :id');
        $query->execute(['id' => $id]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new RuntimeException('Réalisation introuvable');
        }

        $medias = $rmm->findByRealisationId($result['id']);
        $realisation = new Realisation($result["title1"], $result["title2"], $result["title3"], $result["content"], $result["visible"]);

        $realisation->setId($result["id"]);
        $realisation->setMedia($medias ?? []);

        return $realisation;
    }

    public function findLatest(): array
    {
        $rmm = new RealisationMediaManager();
        $query = $this->db->prepare('SELECT * FROM realisation WHERE visible = 1 LIMIT 1 ');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $realisations = [];

        foreach ($result as $item) {
            $medias = $rmm->findByRealisationId($item['id']);
            $realisation = new Realisation($item["title1"], $item["title2"], $item["title3"], $item["content"] ?? '', $item["visible"]);

            $realisation->setId($item["id"]);
            $realisation->setMedia($medias ?? []);

            $realisations[] = $realisation;
        }
        return $realisations;
    }

    // Create a new realisation and associate media
    public function creatRealisation(Realisation $realisation, array $mediaIds): void
    {
        $query = $this->db->prepare('INSERT INTO realisation (title1, title2, title3, content, visible) VALUES (:title1, :title2, :title3, :content, 1)');
        $parameters =
            [
                "title1" => $realisation->getTitle1(),
                "title2" => $realisation->getTitle2(),
                "title3" => $realisation->getTitle3(),
                "content" => $realisation->getContent(),
                "visible" => $realisation->getVisible(),
            ];

        try {
            $query->execute($parameters);
            $realisation->setId($this->db->lastInsertId());

            foreach ($mediaIds as $mediaId) {
                $mm = new MediaManager();
                $rmm = new RealisationMediaManager();
                $mm->createMedia($mediaId);
                $rmm->associateMediaWithRealisation($realisation->getId(), $mediaId);
            }
        } catch (PDOException $e) {
            throw new RuntimeException('Erreur lors de la création de la réalisation : ' . $e->getMessage());
        }
    }

    public function updateRealisation(Realisation $realisation, array $oldNewMediaAssociations): void
    {
        // Mise à jour de la réalisation elle-même
        $query = $this->db->prepare('UPDATE realisation SET title1 = :title1, title2 = :title2, title3 = :title3, content = :content, visible = :visible WHERE id = :id');

        $parameters = [
            "title1" => $realisation->getTitle1(),
            "title2" => $realisation->getTitle2(),
            "title3" => $realisation->getTitle3(),
            "content" => $realisation->getContent(),
            "visible" => $realisation->getVisible(),
            "id" => $realisation->getId(),
        ];

        try {
            // Exécute la mise à jour de la réalisation
            $query->execute($parameters);

            // Mettez à jour les associations de médias pour la réalisation
            $rmm = new RealisationMediaManager();

            // Parcours les associations de médias et les met à jour
            foreach ($oldNewMediaAssociations as $oldMediaId => $newMediaId) {
                $rmm->updateRealisationMediaAssociation($realisation->getId(), $oldMediaId, $newMediaId);
            }
        } catch (PDOException $e) {
            throw new RuntimeException('Erreur lors de la mise à jour de la réalisation avec l\'ID ' . $realisation->getId() . ': ' . $e->getMessage());
        }
    }

    // Delete a realisation and its media associations
    public function deleteRealisation(int $id): void
    {
        try {
            // Delete from realisation_media
            $rmm = new RealisationMediaManager();
            $rmm->deleteRealisationMedia($id, []);
            $query = $this->db->prepare('DELETE FROM realisation_media WHERE realisation_id = :id');
            $query->execute(['id' => $id]);

            // Delete the realisation
            $query = $this->db->prepare('DELETE FROM realisation WHERE id = :id');
            $parameters = ['id' => $id];
            $query->execute($parameters);
        } catch (PDOException $e) {
            throw new RuntimeException('Erreur lors de la suppression de la réalisation : ' . $e->getMessage());
        }
    }
}