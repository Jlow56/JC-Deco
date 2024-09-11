<?php
class ServiceManager extends AbstractManager
{
    public function findAll() : array
    {
        $mm = new MediaManager();

        $query = $this->db->prepare('SELECT * FROM service');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $services = [];

        foreach ($result as $item) {
            $medias = $mm->findByService($item["id"]);
            $service = new Service($item["title1"], $item["title2"], $item["title3"], $item["content"]);
            $service->setId($item["id"]);
            $service->setMedia($medias ?? []); // Utilise un tableau vide si $medias est null
            
            $services[] = $service;

        }
        
        return $services;
    }
}