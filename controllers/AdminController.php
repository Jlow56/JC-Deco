<?php

class AdminController extends AbstractController 
{
    public function dashboard()
    {
        $this->render('dashboard\dashboard.html.twig',
        [
            // "dashboard" => $dashboard,
        ]);
    }
} 
