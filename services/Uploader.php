<?php

class Uploader
{
    private array $extensions = ["jpeg", "jpg", "png", "pdf"];
    private string $uploadFolder = "EstimateUploads"; // Dossier pour les fichiers liés aux devis
    private string $mediaFolder; // Dossier dynamique pour les médias
    private RandomStringGenerator $gen;

    public function __construct(array $get = [])
    {
        $this->gen = new RandomStringGenerator();
        $this->mediaFolder = $this->determineMediaFolder($get);
    }

    /**
     * Détermine le dossier de destination en fonction de la route.
     */
    private function determineMediaFolder(array $get): string
    {
        return match ($get['route'] ?? null) {
            'create-realisation' => "assets/img/Realisation/uploads",
            'create-service' => "assets/img/Service/uploads",
            'create-home' => "assets/img/Accueil/uploads",
            default => throw new Exception("Route non valide ou absente."),
        };
    }
    /**
     * Téléchargement générique.
     */
    public function upload(array $files, string $uploadField): ?Media
    {
        if (isset($files[$uploadField])) {
            try {
                $file = $files[$uploadField];
                $this->validateFile($file);

                $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $newFileName = $this->gen->generate(8);
                $destination = $this->uploadFolder . "/" . $newFileName . "." . $fileExt;

                move_uploaded_file($file['tmp_name'], $destination);
                $this->compressImage($destination, $fileExt);

                return new Media($destination, $file['name'], 1);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
        return null;
    }

    /**
     * Téléchargement des images vers le dossier médias.
     */
    public function uploadPictures(array $files, string $uploadField): ?Media
    {
        if (isset($files[$uploadField]) && !empty($files[$uploadField]['name'])) {
            try {
                $file = $files[$uploadField];
                $this->validateFile($file);

                $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $newFileName = $this->gen->generate(8);
                $destination = $this->mediaFolder . "/" . $newFileName . "." . $fileExt;

                move_uploaded_file($file['tmp_name'], $destination);
                $this->compressImage($destination, $fileExt);

                return new Media($destination, $file['name'], 1);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
        return null;
    }

    /**
     * Valide la taille et l'extension d'un fichier.
     */
    private function validateFile(array $file): void
    {
        $maxSize = 5 * 1024 * 1024; // Taille max : 5 Mo
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if ($file['size'] > $maxSize) {
            throw new Exception("Erreur : Le fichier est trop lourd.");
        }

        if (!in_array($fileExt, $this->extensions)) {
            throw new Exception("Mauvaise extension. Extensions acceptées : " . implode(", ", $this->extensions) . ".");
        }
    }

    /**
     * Compresse les images.
     */
    private function compressImage(string $filePath, string $fileExtension): void
    {
        switch ($fileExtension) {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($filePath);
                imagejpeg($image, $filePath, 75);
                break;
            case 'png':
                $image = imagecreatefrompng($filePath);
                imagepng($image, $filePath, 6);
                break;
            default:
                break; // Aucun traitement pour les autres formats
        }
    }
}