<?php
class RealisationManager extends AbstractManager
{
    public function findAll() : array
    {
        $mm = new MediaManager();

        $query = $this->db->prepare('SELECT * FROM realisation');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $realisations = [];

        foreach ($result as $item)
        {
            $medias = $mm->findByRealisation($item["id"]);
            $realisation = new Realisation($item["title1"], $item["title2"], $item["title3"], $item["content"]);
            
            $realisation->setId($item["id"]);
            $realisation->setMedia($medias ?? []); // Utilise un tableau vide si $medias est null

            $realisations[] = $realisation;
        }
        return $realisations; 
    }
}