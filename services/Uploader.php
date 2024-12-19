<?php

class Uploader
{
    private const ALLOWED_EXTENSIONS = ["jpeg", "jpg", "png", "pdf"];
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // Taille max : 5 Mo
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
        return match ($get['route'] ?? null) 
        {
            "create-realisation" => "assets/img/Realisation/uploads",
            "create-service" => "assets/img/Service/uploads",
            "estimate-register" => "assets/img/EstimateUploads",
            default => throw new Exception("Route non valide ou absente."),
        };
    }

    /**
     * Valide un fichier unique.
     */
    private function validateSingleFile(array $file): void
    {
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if ($file['size'] > self::MAX_FILE_SIZE) {
            throw new Exception("Le fichier '{$file['name']}' est trop volumineux (max : 5 Mo).");
        }

        if (!in_array($fileExt, self::ALLOWED_EXTENSIONS)) {
            throw new Exception("Extension non valide pour '{$file['name']}'. Extensions autorisées : " . implode(", ", self::ALLOWED_EXTENSIONS) . ".");
        }
    }

    /**
     * Compresse les images.
     */
    private function compressImage(string $filePath, string $fileExtension): void
    {
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
                break;
            default:
                // Pas de compression pour les autres formats
                break;
        }
    }

    /**
     * Traite un fichier unique pour l'upload.
     */
    private function processSingleUpload(array $file): Media
    {
        $this->validateSingleFile($file);

        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newFileName = $this->gen->generate(8);
        $destination = $this->mediaFolder . "/" . $newFileName . "." . $fileExt;

        move_uploaded_file($file['tmp_name'], $destination);
        $this->compressImage($destination, $fileExt);

        return new Media($destination, $file['name'], 1);
    }

    /**
     * Télécharge un ou plusieurs fichiers.
     */
    public function upload(array $files, string $uploadField): array
    {
        $uploadedFiles = [];

        if (isset($files[$uploadField]) && !empty($files[$uploadField]['name'])) 
        {
            $file = $files[$uploadField];

            try {
                // Gérer les fichiers multiples
                if (is_array($file['name'])) 
                {
                    foreach ($file['name'] as $index => $fileName) {
                        $uploadedFiles[] = $this->processSingleUpload([
                            'name' => $file['name'][$index],
                            'tmp_name' => $file['tmp_name'][$index],
                            'size' => $file['size'][$index],
                            'error' => $file['error'][$index],
                        ]);
                    }
                }else{
                    // Gérer un fichier unique
                    $uploadedFiles[] = $this->processSingleUpload($file);
                }
            } catch (Exception $e) {
                return ["error" => $e->getMessage()];
            }
        }
        return $uploadedFiles;
    }
}