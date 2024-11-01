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
        if (!isset($_POST["last_name"], $_POST["first_name"], $_POST["adresse"], $_POST["postcode"], $_POST["phone"], $_POST["email"], $_POST["services_type"], $_POST["services"], 
            $_POST["painting_surface_type"], $_POST["color"], $_POST["status"], $_POST["surface_material"], $_POST["pvc_surface_type"]) || empty($_POST["last_name"]) || empty($_POST["first_name"]) 
            || empty($_POST["adresse"]) || empty($_POST["postcode"]) || empty($_POST["phone"]) || empty($_POST["email"]) || empty($_POST["services_type"]) || empty($_POST["services"]) 
            || empty($_POST["painting_surface_type"]) || empty($_POST["color"]) || empty($_POST["status"]) || empty($_POST["surface_material"]) || empty($_POST["pvc_surface_type"]))
        {
            $error = "Veuillez cocher au moin une case sur les champs obligatoires.";
            $this->render('estimate\estimate.html.twig', ['message' => $error]);
            return; 
        }

        if (isset($_POST["last_name"], $_POST["first_name"], $_POST["adresse"], $_POST["postcode"], $_POST["phone"], $_POST["email"], $_POST["services_type"], $_POST["services"], 
            $_POST["painting_surface_type"], $_POST["color"], $_POST["status"], $_POST["surface_material"], $_POST["pvc_surface_type"])) 
        {
            $tokenManager = new CSRFTokenManager();
            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) 
            {
                $em = new EstimateManager();
                // Récupération et nettoyage des données
                $last_name = htmlspecialchars($_POST["last_name"]);
                $first_name = htmlspecialchars($_POST["first_name"]);
                $adresse = htmlspecialchars($_POST["adresse"]);
                $city = htmlspecialchars($_POST["city"]);
                $postcode = htmlspecialchars($_POST["postcode"]);
                $phone = htmlspecialchars($_POST["phone"]);
                $email = htmlspecialchars($_POST["email"]);
                $services_type = htmlspecialchars($_POST["services_type"]);
                $services = htmlspecialchars($_POST["services"]);
                $painting_surface_type = htmlspecialchars($_POST["painting_surface_type"]);
                $created_at = $currentDateTime;
                
                // Champs optionnels
                $painting_surface_type_other = isset($_POST["painting_surface_type_other"]) ? htmlspecialchars($_POST["painting_surface_type_other"]) : null;
                $color = htmlspecialchars($_POST["color"]);
                $what_color = isset($_POST["what_color"]) ? htmlspecialchars($_POST["what_color"]) : null;
                $surface_count = isset($_POST["surface_count"]) ? htmlspecialchars($_POST["surface_count"]) : null;
                $status = htmlspecialchars($_POST["status"]);
                $surface_material = isset($_POST["surface_material"]) ? htmlspecialchars($_POST["surface_material"]) : null;
                $surface_material_other = isset($_POST["surface_material_other"]) ? htmlspecialchars($_POST["surface_material_other"]) : null;
                $pvc_surface_type = htmlspecialchars($_POST["pvc_surface_type"]);
                $date = isset($_POST["date"]) ? htmlspecialchars($_POST["date"]) : null;
                $selected_date = isset($_POST["selected_date"]) ? htmlspecialchars($_POST["selected_date"]) : null;
                $additional = isset($_POST["additional"]) ? htmlspecialchars($_POST["additional"]) : null;
                $created_at = date("Y-m-d H:i:s");
            
                // Gestion des fichiers picture
                $picture = null;

                if (isset($_FILES['picture']) && !empty($_FILES["picture"]["name"])) 
                {
                    $uploader = new Uploader();
                    $picture = $uploader->upload($_FILES, "picture");
                    if ($picture !== null) 
                    {
                        $picture = $picture->getUrl();
                    }
                }
                $estimate = new Estimate($last_name, $first_name,$adresse,$city,$postcode,$phone, $email,$services_type,$services,
                $painting_surface_type,$painting_surface_type_other,$color,$what_color,$surface_count,$status,$surface_material,
                $surface_material_other,$pvc_surface_type,$date,$selected_date,$picture,$additional, $created_at);
                
                unset($_SESSION["error-message"]);
                
                $em->createEstimate($estimate);
                $this->newEstimate();
                
                $successMessage = "Votre demande a bien été transmise. Nous revenons vers vous dans les plus brefs délais.";
                $this->render('estimate\estimate.html.twig', ['message' => $successMessage]);
                // $this->sendEmail();

            }else{
                $_SESSION["error-message"] = "Token CSRF invalide ou expiré. Veuillez rafraîchir la page et réessayer.";
                $this->redirect("estimate");           
            }
        }else{
            $_SESSION["error-message"] = "Merci de compléter les champs obligatoires.";
            $this->redirect("estimate");         
        }
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