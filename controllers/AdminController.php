<?php

class AdminController extends AbstractController 
{
public function dashboard(): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }
        $this->render("admin/dashboard.html.twig", []);
    }

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

    public function deleteEstimate(int $id): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }
        $em = new EstimateManager();
        $em->deleteEstimate($id);
        $this->redirect("estimates-list");
    }

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
    public function deleteContact(int $id): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect(route:"login");
            return;
        }
        $cm = new ContactManager();
        $cm->deleteContact($id);
        $this->redirect(route:'contacts-list');
    }

    
    public function realisationsList(): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect("login");
            return;
        }
        $rm = new RealisationManager();
        $realisations = $rm->findAll();
        $this->render("admin/realisation/realisations-list.html.twig", ["realisations" => $realisations]);
    }

    
    

}