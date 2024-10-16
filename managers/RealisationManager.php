<?php

class RealisationManager extends AbstractManager
{

    public function findAllIfVisible(): array
    {
        $mm = new MediaManager();

        $query = $this->db->prepare('SELECT * FROM realisation WHERE visible = 1');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $realisations = [];

        foreach ($result as $item) {
            $medias = $mm->findVisibleRealisation($item['id']);
            $realisation = new Realisation($item["title1"], $item["title2"], $item["title3"], $item["content"], $item["visible"]);

            $realisation->setId($item["id"]);
            $realisation->setMedia($medias ?? []);

            $realisations[] = $realisation;
        }
        return $realisations;
    }

    /*************************************/
    //**         Admin tools*        **//

    // Used for Admin-List
    public function findAll(): array
    {
        $mm = new MediaManager();

        $query = $this->db->prepare('SELECT * FROM realisation');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $realisations = [];

        foreach ($result as $item) {
            $medias = $mm->findByRealisation($item['id']);
            $realisation = new Realisation($item["title1"], $item["title2"], $item["title3"], $item["content"], $item["visible"]);

            $realisation->setId($item["id"]);
            $realisation->setMedia($medias ?? []);

            $realisations[] = $realisation;
        }
        return $realisations;
    }

    public function findLatest(): array
    {
        $mm = new MediaManager();
        $query = $this->db->prepare('SELECT * FROM realisation WHERE visible = 1 LIMIT 1 ');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $realisations = [];

        foreach ($result as $item) {
            $medias = $mm->findByRealisation($item['id']);
            $realisation = new Realisation($item["title1"], $item["title2"], $item["title3"], $item["content"]);

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
            ];

        try {
            $query->execute($parameters);
            $realisation->setId($this->db->lastInsertId());

            foreach ($mediaIds as $mediaId) {
                $mm = new MediaManager();
                $mm->associateMediaWithRealisation($realisation->getId(), $mediaId);
            }
        } catch (PDOException $e) {
            throw new RuntimeException('Erreur lors de la création de la réalisation : ' . $e->getMessage());
        }
    }

    public function updateRealisation(Realisation $realisation, array $mediaIds): void
    {
        // Préparez la requête pour mettre à jour la réalisation
        $query = $this->db->prepare('UPDATE realisation SET title1 = :title1, title2 = :title2, title3 = :title3, content = :content WHERE id = :id');
        $parameters =
            [
                "title1" => $realisation->getTitle1(),
                "title2" => $realisation->getTitle2(),
                "title3" => $realisation->getTitle3(),
                "content" => $realisation->getContent(),
                "id" => $realisation->getId(),
            ];

        try {
            // Exécutez la mise à jour de la réalisation
            $query->execute($parameters);

            // Mettez à jour les associations de médias pour la réalisation
            $mm = new MediaManager();
            $mm->updateMediaAssociationsForRealisation($realisation->getId(), $mediaIds);
        } catch (PDOException $e) {
            throw new RuntimeException('Erreur lors de la mise à jour de la réalisation avec l\'ID ' . $realisation->getId() . ': ' . $e->getMessage());
        }
    }

    // Delete a realisation and its media associations
    public function deleteRealisation(int $id): void
    {
        try {
            // Delete from realisation_media
            $query = $this->db->prepare('DELETE FROM realisation_media WHERE realisation_id = :id');
            $query->execute(['id' => $id]);

            // Delete the realisation itself
            $query = $this->db->prepare('DELETE FROM realisation WHERE id = :id');
            $parameters = ['id' => $id];
            $query->execute($parameters);
        } catch (PDOException $e) {
            throw new RuntimeException('Erreur lors de la suppression de la réalisation : ' . $e->getMessage());
        }
    }
}