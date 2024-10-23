<?php
class Uploader
{
    private array $extensions = ["jpeg", "jpg", "png", "pdf"];
    private string $uploadFolder = "uploads"; // Pour l'envoi d'un média via le formulaire
    private string $mediasFolder = "assets/img"; // Création d'une réalisation avec photos
    private RandomStringGenerator $gen;

    const MAX_FILE_SIZE = 5 * 1024 * 1024; // Taille maximale du fichier (5 Mo)

    public function __construct()
    {
        $this->gen = new RandomStringGenerator();
    }

    public function uploadFile(array $files, string $uploadField, string $folder): ?Media
    {
        if (isset($files[$uploadField]) && !empty($files[$uploadField]['name'][0])) {
            try {
                foreach ($files[$uploadField]['tmp_name'] as $key => $tmp_name) {
                    if ($files[$uploadField]['error'][$key] === UPLOAD_ERR_OK) {
                        $file_name = $files[$uploadField]['name'][$key];
                        $file_tmp = $files[$uploadField]['tmp_name'][$key];
                        $filesize = $files[$uploadField]["size"][$key];
                        $tabFileName = explode('.', $file_name);
                        $file_ext = strtolower(trim(end($tabFileName)));
                        $newFileName = $this->gen->generate(8);

                        // Vérifier la taille du fichier
                        if ($filesize > self::MAX_FILE_SIZE) {
                            throw new Exception("Erreur : Le fichier {$file_name} est trop lourd.");
                        }

                        // Vérifier l'extension du fichier
                        if (!in_array($file_ext, $this->extensions)) {
                            throw new Exception("Mauvaise extension pour le fichier {$file_name}. Les formats acceptés sont : JPG, JPEG, PDF ou PNG.");
                        }

                        // Créer le dossier s'il n'existe pas
                        if (!is_dir($folder)) {
                            mkdir($folder, 0755, true);
                        }

                        // Déplacer le fichier vers le dossier de destination
                        $url = $folder . "/" . $newFileName . "." . $file_ext;
                        move_uploaded_file($file_tmp, $url);

                        // Compresser l'image si c'est une image
                        $this->compressImage($url, $file_ext);

                        return new Media($url, $file_name, true);
                    }
                }
            } catch (Exception $e) {
                echo $e->getMessage(); // Affichage de l'erreur (vous pouvez changer cela pour utiliser un logger)
                return null;
            }
        }

        return null;
    }

    private function compressImage(string $filePath, string $fileExtension)
    {
        switch ($fileExtension) {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($filePath);
                imagejpeg($image, $filePath, 75); // Qualité de compression de 75%
                break;
            case 'png':
                $image = imagecreatefrompng($filePath);
                imagepng($image, $filePath, 6); // Compression de 6 (sur une échelle de 0 à 9)
                break;
            default:
                break;
        }
    }

    public function upload(array $files, string $uploadField): ?Media
    {
        return $this->uploadFile($files, $uploadField, $this->uploadFolder);
    }

    public function uploadMedias(array $files, string $uploadField): ?Media
    {
        return $this->uploadFile($files, $uploadField, $this->mediasFolder);
    }
}