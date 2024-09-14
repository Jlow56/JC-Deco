<?php
class RealisationController extends AbstractController
{
    public function  Realisations() : void
    {
        $rm = new RealisationManager();
        $realisations = $rm->findAll();
        
        $this->render('realisation\realisation.html.twig',
        ["realisations" => $realisations]);
    }
}