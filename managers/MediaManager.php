<?php
class MediaManager extends AbstractManager
{
    public function findByHome(int $homeId) : ?array
    {
        $query = $this->db->prepare('SELECT media.* FROM media JOIN home_media ON media.id = home_media.media_id WHERE home_media.home_id = :home_id');
        $parameters = ["home_id" => $homeId];
        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $medias = [];

        if ($result) 
        {
            foreach ($result as $item)
            {
                $media = new Media($item["url"], $item["alt"], $item["title"], $item["visible"]);
                $media->setId($item["id"]);
                $medias[] = $media;
            }
        }
        return $medias;
    } 

    public function findByService(int $serviceId) : ?array
    {
        $query = $this->db->prepare('SELECT media.* FROM media JOIN service_media ON media.id = service_media.media_id WHERE service_media.service_id = :service_id');
        $parameters = ["service_id" => $serviceId];
        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $medias = [];

        if($result)
        {
            foreach ($result as $item)
            {
                $media = new Media($item["url"], $item["alt"], $item["title"], $item["visible"]);
                $media->setId($item["id"]);
                $medias[] = $media;
            }
        }
        return $medias;
    }

    public function findByRealisation(int $realisationId) : ?array
    {
        $query = $this->db->prepare('SELECT media.* FROM media JOIN realisation_media ON media.id = realisation_media.media_id WHERE realisation_media.realisation_id = :realisation_id');
        $parameters = ["realisation_id" => $realisationId];
        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $medias = [];

        if ($result) 
        {
            foreach ($result as $item)
            {
                $media = new Media($item["url"], $item["alt"], $item["title"], $item["visible"]);
                $media->setId($item["id"]);
                $medias[] = $media;
            }
        }
        return $medias;
    } 
}