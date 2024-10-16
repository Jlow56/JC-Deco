<?php
class RealisationController extends AbstractController
{
    public function  Realisations() : void
    {
        $rm = new RealisationManager();
        $realisations = $rm->findAllIfVisible();
        
        $this->render('realisations\realisations.html.twig',
        ["realisations" => $realisations]);
    }
}