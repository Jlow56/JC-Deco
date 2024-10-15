<?php

use Twig\Node\Expression\FunctionExpression;

class FormsController extends AbstractController
{
    public function estimate()
    {
        $this->render('estimate\estimate.html.twig', []);
    }

    public function contact()
    {
        $this->render('contact\contact.html.twig', []);
    }

    public function contactRegister()
    {
        if (
            isset($_POST["firstname"]) && isset($_POST["lastname"]) && isset($_POST["phone phoneNumber"]) && isset($_POST["email"]) && isset($_POST["city"]) && isset($_POST["zipCode"])
            && isset($_POST["message"]) && isset($_POST["createdAt"])
        ) {
            $cm = new ContactManager();
            $firstname = htmlspecialchars(string: $_POST["firstname"]);
            $lastname = htmlspecialchars(string: $_POST["lastname"]);
            $phoneNumber = htmlspecialchars(string: $_POST["phoneNumber"]);
            $email = htmlspecialchars(string: $_POST["email"]);
            $city = htmlspecialchars(string: $_POST["city"]);
            $zipCode = htmlspecialchars(string: $_POST["zipCode"]);
            $message = htmlspecialchars(string: $_POST["message"]);
            $contact = new Contact(firstName: $firstname, lastName: $lastname, phoneNumber: $phoneNumber, email: $email, city: $city, zipCode: $zipCode, message: $message, createdAt: date("Y-m-d H:i:s"));
            $cm->createContact($contact);

            if ($this->newContact()) {
                $response = ['success' => true, 'message' => 'Votre message a bien été envoyé. Merci $firstname $lastname pour votre solicitation. L\'équipe de JC-Déco dans les plus bref délais.'];
            } else {
                $response = ['success' => false, 'message' => 'Une erreur est survenue lors de l\'envoi de votre message. Réessayé en remplissant tous les champs obligatoires. Si le problème persiste, veuillez nous excuser et réessayer ultérieurement.'];
            }
            $this->renderJson($response);
        }
    }

    public function estimateRegister()
    {
        if (
            isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["phone"]) && isset($_POST["email"]) && isset($_POST["city"]) && isset($_POST["postcode"])
            && isset($_POST["services_type"]) && isset($_POST["services"]) && isset($_POST["painting_surface_type"]) && isset($_POST["color"]) && isset($_POST["surface_material"])
            && isset($_POST["pvc_surface_type"]) && isset($_POST["status"]) && isset($_POST["adresse"])
        ) {

            $em = new EstimateManager();
            // Clean up received data
            $first_name = htmlspecialchars($_POST["first_name"]);
            $last_name = htmlspecialchars($_POST["last_name"]);
            $phone = htmlspecialchars($_POST["phone"]);
            $email = htmlspecialchars($_POST["email"]);
            $city = htmlspecialchars($_POST["city"]);
            $postcode = htmlspecialchars($_POST["postcode"]);
            $services_type = htmlspecialchars($_POST["services_type"]);
            $services = htmlspecialchars($_POST["services"]);
            $painting_surface_type = htmlspecialchars($_POST["painting_surface_type"]);
            $color = htmlspecialchars($_POST["color"]);
            $surface_material = htmlspecialchars($_POST["surface_material"]);
            $pvc_surface_type = htmlspecialchars($_POST["pvc_surface_type"]);
            $status = htmlspecialchars($_POST["status"]);
            $adresse = htmlspecialchars($_POST["adresse"]); // Address field

            // Optional fields
            $painting_surface_type_other = isset($_POST["painting_surface_type_other"]) ? htmlspecialchars($_POST["painting_surface_type_other"]) : null;
            $what_color = isset($_POST["what_color"]) ? htmlspecialchars($_POST["what_color"]) : null;
            $number_of_surface = isset($_POST["numberOfSurface"]) ? htmlspecialchars($_POST["numberOfSurface"]) : null;
            $surface_material_other = isset($_POST["surface_material_other"]) ? htmlspecialchars($_POST["surface_material_other"]) : null;
            $date = isset($_POST["date"]) ? htmlspecialchars($_POST["date"]) : null;
            $selected_date = isset($_POST["selected_date"]) ? htmlspecialchars($_POST["selected_date"]) : null;
            $photos = isset($_POST["photos"]) ? htmlspecialchars($_POST["photos"]) : null;
            $additional = isset($_POST["additional"]) ? htmlspecialchars($_POST["additional"]) : null;
            $created_at = date("Y-m-d H:i:s");

            // Create Estimate object based on the `devis_form` table structure
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
                $painting_surface_type_other,
                $color,
                $what_color,
                $number_of_surface,
                $status,
                $surface_material,
                $surface_material_other,
                $pvc_surface_type,
                $date,
                $selected_date,
                $photos,
                $additional,
                $created_at,
            );

            $em->createEstimate($estimate);

            if ($this->newEstimate()) {
                $response =
                [
                    'success' => true,
                    'message' => "Votre demande de devis a bien été envoyée. Merci $first_name $last_name pour votre sollicitation. L'équipe de JC-Déco vous contactera dans les plus brefs délais."
                ];
            } else {
                $response =
                    [
                        'success' => false,
                        'message' => "Une erreur est survenue lors de l'envoi de votre demande de devis. Veuillez réessayer en remplissant tous les champs obligatoires. Si le problème persiste, veuillez nous excuser et réessayer ultérieurement."
                    ];
            }

            $this->renderJson($response);
        }
    }
}