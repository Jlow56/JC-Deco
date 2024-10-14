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
    public function deleteMessage(int $id): void
    {
        if (!isset($_SESSION["user"])) {
            $this->redirect(route:"login");
            return;
        }
        $cm = new ContactManager();
        $cm->deleteContact($id);
        $this->redirect(route:'contacts-list');
    }
}