<?php

class FormsController extends AbstractController
{
    public function devis()
    {
        $this->render('devis\devis.html.twig',
        [
            // "devis" => $devis,
        ]);
    }

    public function contact()
    {
        // $cm = new ContactManager();
        // $contact = $cm->findAll();
        $this->render('contact\contact.html.twig',
        [
            // "contact" => $contact,
        ]);
    }
}