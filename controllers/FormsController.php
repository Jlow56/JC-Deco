<?php

class FormsController extends AbstractController
{
    public function estimate()
    {
        $this->render(
            'estimate\estimate.html.twig',
            [
                // "estimate" => $estimate,
            ]
        );
    }

    public function contact()
    {
        // $cm = new ContactManager();
        // $contact = $cm->findAll();
        $this->render(
            'contact\contact.html.twig',
            [
                // "contact" => $contact,
            ]
        );
    }
}