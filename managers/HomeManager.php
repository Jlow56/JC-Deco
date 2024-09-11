<?php
class HomeManager extends AbstractManager
{
    public function findAll() : array
    {
        $mm = new MediaManager();

        $query = $this->db->prepare('SELECT * FROM home');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $homes = [];

        foreach ($result as $item) {
            $medias = $mm->findByHome($item["id"]);
            $home = new Home($item["title1"], $item["title2"], $item["title3"], $item["content"]);
            $home->setId($item["id"]);
            $home->setMedia($medias ?? []); // Utilise un tableau vide si $medias est null

            $homes[] = $home;
        }
        return $homes;
    }
}