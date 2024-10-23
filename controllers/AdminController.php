<?php

class AdminController extends AbstractController
{
    public function dashboard(): void
    {
        if (!isset($_SESSION["user"])) {
            $_SESSION["error-message"] = "Vous devez être connecté pour accéder au tableau de bord.";
            $this->redirect("login");
        }
        $this->render("admin/dashboard.html.twig", []);
    }
    /*************************************************************************************************************/
    //****************************//
    //**       ESTIMATE         **//            
    //****************************//
    public function estimatesList(): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }
        $em = new EstimateManager();
        $estimates = $em->findAll();
        $this->render("admin/estimate/estimates-list.html.twig", ["estimates" => $estimates]);
    }

    public function showEstimate(int $id): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }
        $em = new EstimateManager();
        $estimate = $em->getEstimateById($id);
        $this->render("admin/estimate/show-estimate.html.twig", ["estimate" => $estimate]);
    }

    public function updateEstimate(int $id): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }

        $em = new EstimateManager();
        $estimate = $em->getEstimateById($id); // Récupérer l'estimation par ID

        if (!$estimate) {
            $_SESSION['error'] = "L'estimation avec cet ID n'existe pas.";
            $this->redirect("estimates-list");
            return;
        }

        // Si c'est une requête POST, nous traitons le formulaire
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Récupérer les données POST et mettre à jour l'objet Estimate
            try {
                $estimate->setLastName($_POST['last_name']);
                $estimate->setFirstName($_POST['first_name']);
                $estimate->setAdresse($_POST['adresse']);
                $estimate->setCity($_POST['city']);
                $estimate->setPostcode($_POST['postcode']);
                $estimate->setPhone($_POST['phone']);
                $estimate->setEmail($_POST['email']);
                $estimate->setServicesType($_POST['services_type']);
                $estimate->setServices($_POST['services']);
                $estimate->setPaintingSurfaceType($_POST['painting_surface_type']);
                $estimate->setPaintingSurfaceTypeOther($_POST['painting_surface_type_other'] ?? null);
                $estimate->setColor($_POST['color']);
                $estimate->setWhatColor($_POST['what_color'] ?? null);
                $estimate->setNumberOfSurface($_POST['number_of_surface'] ?? null);
                $estimate->setStatus($_POST['status']);
                $estimate->setSurfaceMaterial($_POST['surface_material']);
                $estimate->setSurfaceMaterialOther($_POST['surface_material_other'] ?? null);
                $estimate->setPvcSurfaceType($_POST['pvc_surface_type']);
                $estimate->setDate($_POST['date']);
                $estimate->setSelectedDate($_POST['selected_date'] ?? null);
                $estimate->setPhotos($_POST['photos'] ?? null);
                $estimate->setAdditional($_POST['additional'] ?? null);

                // Mettre à jour l'estimation dans la base de données
                $em->updateEstimate($estimate);

                // Message de succès
                $_SESSION['success'] = "L'estimation a été mise à jour avec succès !";
                $this->redirect("show-estimate?id=" . $estimate->getId());
            } catch (Exception $e) {
                // Message d'échec
                $_SESSION['error'] = "Une erreur s'est produite lors de la mise à jour de l'estimation.";
                $this->redirect("update-estimate?id=" . $estimate->getId());
            }
            return;
        }

        // Afficher le formulaire de mise à jour
        $this->render("admin/estimate/update-estimate.html.twig", ["estimate" => $estimate]);
    }

    public function deleteEstimate(int $id): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }

        $em = new EstimateManager();

        try {
            $em->deleteEstimate($id);
            $_SESSION['success'] = "L'estimation a été supprimée avec succès.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Une erreur s'est produite lors de la suppression de l'estimation.";
        }

        $this->redirect("estimates-list");
    }
    /*************************************************************************************************************/
    //****************************//
    //**       CONTACT          **//            
    //****************************//
    public function contactsList(): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }
        $cm = new ContactManager();
        $contactes = $cm->findAll();
        $this->render("admin/contact/contacts-list.html.twig", ["contactes" => $contactes]);
    }

    public function showContact(int $id): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }
        $cm = new ContactManager();
        $contact = $cm->getContactById($id);
        $this->render("admin/contact/show-contact.html.twig", ["contact" => $contact]);
    }

    public function updateContact(int $id): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }

        $cm = new ContactManager();
        $contact = $cm->getContactById($id); // Récupérer le contact par ID

        if (!$contact) {
            $_SESSION['error'] = "Le contact avec cet ID n'existe pas.";
            $this->redirect("contacts-list");
            return;
        }

        // Si c'est une requête POST, nous traitons le formulaire
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Récupérer les données POST et mettre à jour l'objet Contact
            try {
                $contact->setFirstName($_POST['first_name']);
                $contact->setLastName($_POST['last_name']);
                $contact->setPhoneNumber($_POST['phoneNumber']);
                $contact->setEmail($_POST['email']);
                $contact->setCity($_POST['city']);
                $contact->setZipCode($_POST['zipCode']);
                $contact->setMessage($_POST['message']);
                // Mettre à jour le contact dans la base de données
                $cm->updateContact($contact);

                // Message de succès
                $_SESSION['success'] = "Le contact a été mis à jour avec succès !";
                $this->redirect("show-contact?id=" . $contact->getId());
            } catch (Exception $e) {
                // Message d'échec
                $_SESSION['error'] = "Une erreur s'est produite lors de la mise à jour du contact.";
                $this->redirect("update-contact?id=" . $contact->getId());
            }
            return;
        }
        // Afficher le formulaire de mise à jour
        $this->render("admin/contact/update-contact.html.twig", ["contact" => $contact]);
    }

    public function deleteContact(int $id): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect(route: "login");
            return;
        }
        $cm = new ContactManager();

        try {
            $cm->deleteContact($id);
            $_SESSION['success'] = "Le contact a été supprimé avec succès.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Une erreur s'est produite lors de la suppression du contact.";
        }

        $this->redirect(route: 'contacts-list');
    }
    /*************************************************************************************************************/
    //****************************//
    //**       REALISATION      **//            
    //****************************//
    public function realisationsList(): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }
        $rm = new RealisationManager();
        $realisations = $rm->findAll();
        $this->render("admin/realisations/realisations-list.html.twig", ["realisations" => $realisations]);
    }

    public function showRealisation(int $id): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }
        $rm = new RealisationManager();
        $realisation = $rm->getRealisationById($id);
        $this->render("admin/realisations/show-realisation.html.twig", ["realisation" => $realisation]);
    }

    public function createRealisation(): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }
        $this->render("admin/realisation/create-realisation.html.twig", []);
    }

    public function handleRealisationCreation()
{
    if (!isset($_SESSION["user"])) 
    {
        $this->redirect("login");
        return;
    }

    // Récupérer les données de la réalisation depuis le formulaire
    $realisation = new Realisation(
        $_POST['title1'] ?? '',
        $_POST['title2'] ?? '',
        $_POST['title3'] ?? '',
        $_POST['description'] ?? '',
        $_POST['visible'] ?? 1
    );
    $mediaIds = [];

    // Créer une instance de Uploader
    $uploader = new Uploader();

    // Traitement des fichiers uploadés
    if (!empty($_FILES['mediaFiles']['name'][0])) 
    {
        foreach ($_FILES['mediaFiles']['tmp_name'] as $key => $tmp_name) 
        {
            if ($_FILES['mediaFiles']['error'][$key] === UPLOAD_ERR_OK) 
            {
                $media = $uploader->uploadMedias($_FILES, 'mediaFiles');

                if ($media !== null) 
                {
                    // Ajouter l'ID du média dans le tableau des associations
                    $mediaIds[] = $media->getId();
                } 
                else 
                {
                    $_SESSION['error'] = "Erreur lors du téléchargement de certains fichiers.";
                }
            }
        }
    }

    // Enregistrer la réalisation avec les médias associés
    $realisationManager = new RealisationManager();
    $realisationManager->creatRealisation($realisation, $mediaIds);
}

    public function updateRealisation(int $id): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }

        $rm = new RealisationManager();
        $realisation = $rm->getRealisationById($id); // Récupérer la réalisation par ID

        if (!$realisation) {
            $_SESSION['error'] = "La réalisation avec cet ID n'existe pas.";
            $this->redirect("realisations-list");
            return;
        }

        // Si c'est une requête POST, nous traitons le formulaire
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Récupérer les données POST et mettre à jour l'objet Realisation
            try {
                $realisation->setTitle1($_POST['title1']);
                $realisation->setTitle2($_POST['title2']);
                $realisation->setTitle3($_POST['title3']);
                $realisation->setContent($_POST['content']);
                $realisation->setVisible($_POST['visible']);

                // Mettre à jour la réalisation dans la base de données
                $rm->updateRealisation($realisation, $_POST['media_ids']);

                // Message de succès
                $_SESSION['success'] = "La réalisation a été mise à jour avec succès !";
                $this->redirect("show-realisation?id=" . $realisation->getId());
            } catch (Exception $e) {
                // Message d'échec
                $_SESSION['error'] = "Une erreur s'est produite lors de la mise à jour de la réalisation.";
                $this->redirect("update-realisation?id=" . $realisation->getId());
            }
            return;
        }

        // Afficher le formulaire de mise à jour
        $this->render("admin/realisation/update-realisation.html.twig", ["realisation" => $realisation]);
    }

    public function deleteRealisation(int $id): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }

        $rm = new RealisationManager();

        try {
            $rm->deleteRealisation($id);
            $_SESSION['success'] = "La réalisation a été supprimée avec succès.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Une erreur s'est produite lors de la suppression de la réalisation.";
        }

        $this->redirect("realisations-list");
    }

    /*************************************************************************************************************/
    //****************************//
    //**        SERVICE         **//            
    //****************************//
    public function servicesList(): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }
        $sm = new ServiceManager();
        $services = $sm->findAll();
        $this->render("admin/service/services-list.html.twig", ["services" => $services]);
    }

    public function showService(int $id): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }
        $sm = new ServiceManager();
        $service = $sm->getServiceById($id);
        $this->render("admin/service/show-service.html.twig", ["service" => $service]);
    }

    public function createService(): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $sm = new ServiceManager();
            $service = new Service(
                $_POST['title1'],
                $_POST['title2'],
                $_POST['title3'],
                $_POST['content'],
                $_POST['visible'],
            );

            try {
                $sm->createService($service, $_POST['media_ids']);
                $_SESSION['success'] = "Le service a été créé avec succès !";
                $this->redirect("services-list");
            } catch (Exception $e) {
                $_SESSION['error'] = "Une erreur s'est produite lors de la création du service.";
                $this->redirect("create-service");
            }
            return;
        }

        $this->render("admin/service/create-service.html.twig", []);
    }

    public function updateService(int $id): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }

        $sm = new ServiceManager();
        $service = $sm->getServiceById($id); // Récupérer le service par ID

        if (!$service) {
            $_SESSION['error'] = "Le service avec cet ID n'existe pas.";
            $this->redirect("services-list");
            return;
        }

        // Si c'est une requête POST, nous traitons le formulaire
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Récupérer les données POST et mettre à jour l'objet Service
            try {
                $service->setTitle1($_POST['title']);
                $service->setTitle2($_POST['title2']);
                $service->setTitle3($_POST['title3']);
                $service->setContent($_POST['content']);
                $service->setVisible($_POST['visible']);

                // Mettre à jour le service dans la base de données
                $sm->updateService($service, $_POST['media_ids']);

                // Message de succès
                $_SESSION['success'] = "Le service a été mis à jour avec succès !";
                $this->redirect("show-service?id=" . $service->getId());
            } catch (Exception $e) {
                // Message d'échec
                $_SESSION['error'] = "Une erreur s'est produite lors de la mise à jour du service.";
                $this->redirect("update-service?id=" . $service->getId());
            }
            return;
        }

        // Afficher le formulaire de mise à jour
        $this->render("admin/service/update-service.html.twig", ["service" => $service]);
    }

    public function deleteService(int $id): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }

        $sm = new ServiceManager();

        try {
            $sm->deleteService($id);
            $_SESSION['success'] = "Le service a été supprimé avec succès.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Une erreur s'est produite lors de la suppression du service.";
        }

        $this->redirect("services-list");
    }

    /*************************************************************************************************************/

}