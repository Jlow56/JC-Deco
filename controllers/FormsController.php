<?php

class FormsController extends AbstractController
{   //************************//
    // **      Estimate     **//
    //************************//
    public function estimate(): void
    {
        $this->render('estimate\estimate.html.twig', []);
    }

    public function estimateRegister(): void
    {
        $currentDateTime = date('Y-m-d H:i:s');

        // Vérification des champs obligatoires
        if (
            !isset(
            $_POST["last_name"],
            $_POST["first_name"],
            $_POST["adresse"],
            $_POST["postcode"],
            $_POST["phone"],
            $_POST["email"],
            $_POST["services_type"],
            $_POST["services"],
            $_POST["painting_surface_type"],
            $_POST["color"],
            $_POST["status"],
            $_POST["surface_material"],
            $_POST["pvc_surface_type"]
        ) || empty($_POST["last_name"]) || empty($_POST["first_name"])
            || empty($_POST["adresse"]) || empty($_POST["postcode"]) || empty($_POST["phone"]) || empty($_POST["email"]) || empty($_POST["services_type"]) || empty($_POST["services"])
            || empty($_POST["painting_surface_type"]) || empty($_POST["color"]) || empty($_POST["status"]) || empty($_POST["surface_material"]) || empty($_POST["pvc_surface_type"])
        ) {
            $error = "Veuillez cocher au moins une case dans les champs obligatoires.";
            $this->render('estimate\estimate.html.twig', ['message' => $error]);
            return;
        }

        $tokenManager = new CSRFTokenManager();
        if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
            $em = new EstimateManager();

            // Récupération et nettoyage des données
            $last_name = htmlspecialchars($_POST["last_name"]);
            $first_name = htmlspecialchars($_POST["first_name"]);
            $adresse = htmlspecialchars($_POST["adresse"]);
            $city = htmlspecialchars($_POST["city"]);
            $postcode = htmlspecialchars($_POST["postcode"]);
            $phone = htmlspecialchars($_POST["phone"]);
            $email = htmlspecialchars($_POST["email"]);

            // Conversion des tableaux en chaînes si nécessaire
            $services_type = is_array($_POST['services_type']) ? implode(', ', array_map('htmlspecialchars', $_POST['services_type'])) : htmlspecialchars($_POST['services_type']);
            $services = is_array($_POST['services']) ? implode(', ', array_map('htmlspecialchars', $_POST['services'])) : htmlspecialchars($_POST['services']);
            $painting_surface_type = is_array($_POST['painting_surface_type']) ? implode(', ', array_map('htmlspecialchars', $_POST['painting_surface_type'])) : htmlspecialchars($_POST['painting_surface_type']);
            $color = is_array($_POST['color']) ? implode(', ', array_map('htmlspecialchars', $_POST['color'])) : htmlspecialchars($_POST['color']);
            $status = is_array($_POST['status']) ? implode(', ', array_map('htmlspecialchars', $_POST['status'])) : htmlspecialchars($_POST['status']);
            $pvc_surface_type = is_array($_POST['pvc_surface_type']) ? implode(', ', array_map('htmlspecialchars', $_POST['pvc_surface_type'])) : htmlspecialchars($_POST['pvc_surface_type']);
            $created_at = $currentDateTime;

            // Champs optionnels
            $what_color = isset($_POST["what_color"]) ? htmlspecialchars($_POST["what_color"]) : null;
            $surface_count = isset($_POST["surface_count"]) ? htmlspecialchars($_POST["surface_count"]) : null;
            $surface_material = isset($_POST["surface_material"]) ? (is_array($_POST["surface_material"]) ? implode(', ', array_map('htmlspecialchars', $_POST["surface_material"])) : htmlspecialchars($_POST["surface_material"])) : null;
            $surface_material_other = isset($_POST["surface_material_other"]) ? htmlspecialchars($_POST["surface_material_other"]) : null;
            $date = isset($_POST["date"]) && is_array($_POST["date"]) ? implode(', ', array_map('htmlspecialchars', $_POST["date"])) : null;
            $selected_date = isset($_POST["selected_date"]) ? htmlspecialchars($_POST["selected_date"]) : null;
            $additional = isset($_POST["additional"]) ? htmlspecialchars($_POST["additional"]) : null;
            // Gestion des fichiers picture
            $picture = null;
            if (isset($_FILES['picture']) && !empty($_FILES["picture"]["name"])) {
                $get = $_GET;
                $uploader = new Uploader($get);
                $picture = $uploader->upload($_FILES, "picture");
                if ($picture !== null) {
                    $picture = $picture->getUrl();
                }
            }

            // Création de l'objet Estimate
            $estimate = new Estimate(
                $last_name,
                $first_name,
                $adresse,
                $city,
                $postcode,
                $phone,
                $email,
                $services_type,
                $services,
                $painting_surface_type,
                null, // Optionnel, ajuster si nécessaire
                $color,
                $what_color,
                $surface_count,
                $status,
                $surface_material,
                $surface_material_other,
                $pvc_surface_type,
                $date,
                $selected_date,
                $picture,
                $additional,
                $created_at
            );

            // Enregistrement de l'estimation
            unset($_SESSION["error-message"]);
            $em->createEstimate($estimate);

            // Message de succès
            $successMessage = "Votre demande a bien été transmise. Nous revenons vers vous dans les plus brefs délais.";
            $this->render('estimate\estimate.html.twig', ['message' => $successMessage]);
        } else {
            $_SESSION["error-message"] = "Token CSRF invalide ou expiré. Veuillez rafraîchir la page et réessayer.";
            $this->redirect("estimate");
        }
    }


    // Méthode pour valider les champs obligatoires
    private function validateRequiredFields(array $fields): bool
    {
        foreach ($fields as $field) {
            if (empty($_POST[$field])) {
                return false;
            }
        }
        return true;
    }
    // Méthode pour obtenir un champ optionnel
    private function getOptionalField(string $fieldName): ?string
    {
        $value = $_POST[$fieldName] ?? null;

        if (is_array($value)) {
            // Convertir le tableau en une chaîne, si plusieurs valeurs sont envoyées pour un même champ
            return implode(', ', array_map('htmlspecialchars', $value));
        }

        return $value !== null ? htmlspecialchars($value) : null;
    }

    // Méthode pour gérer l'upload de fichier
    private function handleFileUpload(string $fileKey): ?string
    {
        if (isset($_FILES[$fileKey]) && !empty($_FILES[$fileKey]["name"])) {
            $uploader = new Uploader();
            $file = $uploader->upload($_FILES, $fileKey);
            return $file ? $file->getUrl() : null;
        }
        return null;
    }

    // Méthode pour nettoyer l'entrée, prenant en charge les tableaux et les chaînes de caractères
    private function sanitizeInput($input)
    {
        if (is_array($input)) {
            return array_map('htmlspecialchars', $input);
        }
        return htmlspecialchars($input);
    }
    /****************************************************/
    //************************//
    // **      Contact      **//
    //************************//
    public function contact(): void
    {
        $this->render('contact\contact.html.twig', []);
    }

    public function contactRegister(): void
    {
        $currentDateTime = date('Y-m-d H:i:s');

        // Vérification des champs obligatoires
        if (isset($_POST["firstName"], $_POST["lastName"], $_POST["phoneNumber"], $_POST["email"], $_POST["city"], $_POST["zipCode"], $_POST["message"])) {
            $tokenManager = new CSRFTokenManager();

            // Validation du token CSRF
            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                $cm = new ContactManager();

                // Nettoyage des données
                $firstname = htmlspecialchars($_POST["firstName"]);
                $lastname = htmlspecialchars($_POST["lastName"]);
                $phoneNumber = htmlspecialchars($_POST["phoneNumber"]);
                $email = htmlspecialchars($_POST["email"]);
                $city = htmlspecialchars($_POST["city"]);
                $zipCode = htmlspecialchars($_POST["zipCode"]);
                $message = htmlspecialchars($_POST["message"]);
                $created_at = $currentDateTime;

                // Création de l'objet Contact
                $contact = new Contact(
                    firstName: $firstname,
                    lastName: $lastname,
                    phoneNumber: $phoneNumber,
                    email: $email,
                    city: $city,
                    zipCode: $zipCode,
                    message: $message,
                    createdAt: $created_at
                );

                // Enregistrement du contact
                $cm->createContact($contact);
                // Vérification de la réussite de l'enregistrement
                if ($this->newContact()) {
                    $response = [
                        'status' => 'OK',
                        'message' => "Votre message a bien été envoyé. Merci $firstname $lastname pour votre sollicitation. L'équipe de JC-Déco vous contactera dans les plus brefs délais."
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => "Une erreur est survenue lors de l'envoi de votre message. Réessayez en remplissant tous les champs obligatoires. Si le problème persiste, veuillez nous excuser et réessayer ultérieurement."
                    ];
                }
            } else {
                http_response_code(405);
                $response = [
                    'status' => 'error',
                    'message' => "Token CSRF invalide ou expiré. Veuillez rafraîchir la page et réessayer."
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => "Merci de compléter les champs obligatoires."
            ];
        }

        // Envoi de la réponse en JSON
        $this->renderJson($response);
    }
}