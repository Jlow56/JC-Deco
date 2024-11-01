<?php

class Uploader
{
    private array $extensions = ["jpeg", "jpg", "png", "pdf"];
    private string $uploadFolder = "uploads"; /* pour l'envoi d'un photo via  le formulaire de devis */
    private string $picturesFolder = "assets/img";
    private RandomStringGenerator $gen;

    public function __construct()
    {
        $this->gen = new RandomStringGenerator();
    }
    /**
     * @param array $files // le tableau $_FILES
     * @param string $uploadField // le nom du champ du formulaire
     *
     */
    public function upload($files, string $uploadField): ?Media
    {
        if (isset($files[$uploadField])) 
        {
            try 
            {
                $visible = 1;
                $file_name = $files[$uploadField]['name'];
                $file_tmp = $files[$uploadField]['tmp_name'];
                $maxsize = 5 * 1024 * 1024;
                $filesize = $_FILES[$uploadField]["size"];
                $tabFileName = explode('.', $file_name);
                $file_ext = strtolower(trim(end($tabFileName)));
                $newFileName = $this->gen->generate(8);

                if ($filesize > $maxsize) {
                    throw new Exception("Erreur : Le fichier est trop lourd.");
                } 
                else if (in_array($file_ext, $this->extensions) === false) 
                {
                    throw new Exception("Mauvaise extension. Les formats suivants sont acceptés : JPG, JPEG, PDF or PNG file.");
                } 
                else 
                {
                    $url = $this->uploadFolder . "/" . $newFileName . "." . $file_ext;
                    move_uploaded_file($file_tmp, $url);
                    $this->compressImage($url, $file_ext);
                    return new Media($url, $file_name, $visible);
                }
            } 
            catch (Exception $e) 
            {
                return null;
            }
        }
        return null;
    }

    private function compressImage(string $filePath, string $fileExtension)
    {
        // Vérifie le type de fichier
        switch ($fileExtension) 
        {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($filePath);
                imagejpeg($image, $filePath, 75);
                break;
            case 'png':
                $image = imagecreatefrompng($filePath);
                imagepng($image, $filePath, 6);
            default:
                break;
        }
    }

    public function uploadPictures(array $files, string $uploadField): ?Media
    {
        if (isset($files[$uploadField]) && !empty($files[$uploadField]['name'])) 
        {
            try 
            {
                $visible = 1;
                $file_name = $files[$uploadField]['name'];
                $file_tmp = $files[$uploadField]['tmp_name'];
                $maxsize = 5 * 1024 * 1024;
                $filesize = $_FILES[$uploadField]["size"];
                $tabFileName = explode('.', $file_name);
                $file_ext = strtolower(trim(end($tabFileName)));

                $newFileName = $this->gen->generate(8);

                if ($filesize > $maxsize) 
                {
                    throw new Exception("Erreur : Le fichier est trop lourd.");
                } else if (!in_array($file_ext, $this->extensions)) 
                {
                    throw new Exception("Mauvaise extension. Les formats suivants sont acceptés : JPG, JPEG, PDF or PNG file.");
                } 
                else 
                {
                    $url = $this->picturesFolder . "/" . $newFileName . "." . $file_ext;
                    move_uploaded_file($file_tmp, $url);
                    $this->compressImage($url, $file_ext);
                    return new Media($url, $file_name, $visible);
                }
            } 
            catch (Exception $e) 
            {
                echo $e->getMessage();
                return null;
            }
        }
        return null;
    }
}