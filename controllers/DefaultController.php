<?php
class DefaultController extends AbstractController
{
    public function home()
    {
        $hm = new HomeManager();
        $homes = $hm->findAll();
        $this->render('default/home.html.twig',
        ["homes" => $homes,]);
    }
    
    public function notFound()
    {
        $this->render("default/404.html.twig", 
        ["title" => "404 : Page introuvable"]);
    }
}