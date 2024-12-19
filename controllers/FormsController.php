<?php

class FormsController extends AbstractController
{   //************************//
    // **      Estimate     **//
    //************************//
    public function estimate(): void
    {
        $this->render('estimate\estimate.html.twig', []);
    }

    // Méthode pour nettoyer l'entrée, prenant en charge les tableaux et les chaînes de caractères
    private function sanitizeInput($input): array|string
    {
        if (is_array($input)) {
            return array_map('htmlspecialchars', $input);
        }
        return htmlspecialchars($input);
    }

    public function estimateRegister(): void
    {
        $currentDateTime = date('Y-m-d H:i:s');
        // Vérification des champs obligatoires
        $requiredFields = [
            "last_name",
            "first_name",
            "adresse",
            "city",
            "postcode",
            "phone",
            "email",
            ["services_type"],
            ["services"],
            ["painting_surface_type"],
            ["color"],
            ["status"],
            ["surface_material"],
            ["pvc_surface_type"]
        ];

        foreach ($requiredFields as $field) 
        {
            if (is_array($field)) 
            {
                foreach ($field as $subField) 
                {
                    if (empty($_POST[$subField]) || (is_array($_POST[$subField]) && empty(array_filter($_POST[$subField])))) 
                    {
                        $error = "Veuillez remplir tous les champs obligatoires.";
                        $this->render('estimate\estimate.html.twig', ['message' => $error]);
                        return;
                    }
                }
            } 
            else {
                if (empty($_POST[$field])) 
                {
                    $error = "Veuillez remplir tous les champs obligatoires.";
                    $this->render('estimate\estimate.html.twig', ['message' => $error]);
                    return;
                }
            }
        }

        // Validation du token CSRF
        $tokenManager = new CSRFTokenManager();
        if (isset($_POST["csrf-token"]) && !$tokenManager->validateCSRFToken($_POST["csrf-token"])) 
        {
            $_SESSION["error-message"] = "Token CSRF invalide ou expiré. Veuillez rafraîchir la page et réessayer.";
            $this->redirect("estimate");
            return;
        }

        // Récupération et nettoyage des données
        $data = [
            'last_name' => htmlspecialchars($_POST["last_name"]),
            'first_name' => htmlspecialchars($_POST["first_name"]),
            'adresse' => htmlspecialchars($_POST["adresse"]),
            'city' => htmlspecialchars($_POST["city"] ?? ''),
            'postcode' => htmlspecialchars($_POST["postcode"]),
            'phone' => htmlspecialchars($_POST["phone"]),
            'email' => htmlspecialchars($_POST["email"]),
            'services_type' => $this->sanitizeInput($_POST["services_type"]),
            'services' => $this->sanitizeInput($_POST["services"]),
            'painting_surface_type' => $this->sanitizeInput($_POST["painting_surface_type"]),
            'color' => $this->sanitizeInput($_POST["color"]),
            'status' => $this->sanitizeInput($_POST["status"]),
            'surface_material' => $this->sanitizeInput($_POST["surface_material"]),
            'pvc_surface_type' => $this->sanitizeInput($_POST["pvc_surface_type"]),
            'what_color' => htmlspecialchars($_POST["what_color"] ?? ''),
            'surface_count' => htmlspecialchars($_POST["surface_count"] ?? ''),
            'surface_material_other' => htmlspecialchars($_POST["surface_material_other"] ?? ''),
            'date' => $this->sanitizeInput($_POST["date"] ?? ''),
            'selected_date' => htmlspecialchars($_POST["selected_date"] ?? ''),
            'additional' => htmlspecialchars($_POST["additional"] ?? ''),
            'created_at' => $currentDateTime
        ];
        // Gestion des fichiers (photos)
        $pictures = [];
        if (isset($_FILES['picture']) && !empty($_FILES["picture"]["name"][0])) {
            try {
                $uploader = new Uploader($_GET);
                $uploadedFiles = $uploader->upload($_FILES, "picture");

                // Conserver toutes les URLs des fichiers valides
                foreach ($uploadedFiles as $file) {
                    $pictures[] = $file->getUrl();
                }
            } catch (Exception $e) {
                $error = "Erreur lors du téléchargement des fichiers : " . $e->getMessage();
                $this->render('estimate\estimate.html.twig', ['message' => $error]);
                return;
            }
        }

        // Création de l'objet Estimate
        $estimate = new Estimate(
            $data['last_name'],
            $data['first_name'],
            $data['adresse'],
            $data['city'],
            $data['postcode'],
            $data['phone'],
            $data['email'],
            implode(', ', $data['services_type']), // Convertit le tableau en chaîne
            implode(', ', $data['services']),
            implode(', ', $data['painting_surface_type']),
            null,
            implode(', ', $data['color']),
            $data['what_color'],
            $data['surface_count'],
            implode(', ', $data['status']),
            implode(', ', $data['surface_material']),
            $data['surface_material_other'],
            implode(', ', $data['pvc_surface_type']),
            implode(', ', $data['date']), // Si "date" est aussi un tableau
            $data['selected_date'],
            implode(', ', $pictures), // Conserve les URLs comme chaîne
            $data['additional'],
            $data['created_at']
        );
        // Enregistrement dans la base de données
        $em = new EstimateManager();
        $em->createEstimate($estimate);

        // Message de succès
        $successMessage = "Votre demande a bien été transmise. Nous revenons vers vous dans les plus brefs délais.";
        $this->render('estimate\estimate.html.twig', ['message' => $successMessage]);
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