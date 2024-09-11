<?php
class ServiceController extends AbstractController
{
    public function services() : void
    {
        $sm = new ServiceManager();
        $services = $sm->findAll();
        
        $this->render('services/services.html.twig',
        [
            "services" => $services
        ]);
    }
}