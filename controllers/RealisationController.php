<?php
class RealisationController extends AbstractController
{
    public function  Realisations() : void
    {
        $rm = new RealisationManager();
        $realisation = $rm->findAll();
        
        $this->render('realisation\realisation.html.twig',[
            "realisation" => $realisation,
        ]);
    }
}