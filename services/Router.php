<?php
class Router
{
    public function handleRequest(array $get): void
    {
        $dc = new DefaultController();
        $sc = new ServiceController();
        $rc = new RealisationController();
        $fc = new FormsController();
        $atc = new AuthController();
        $ac = new AdminController();

        //******************//
        //***   Public  ***//
        //****************//
        // Home
        if (!isset($get["route"])) 
        {
            $dc->home();
        } 
        else if (isset($get["route"]) && $get["route"] === "home") 
        {
            $dc->home();
        } 
        // Services
        else if (isset($get["route"]) && $get["route"] === "services") 
        {
            $sc->services();
        }
        // Realisations
        else if (isset($get["route"]) && $get["route"] === "realisations") 
         {
            $rc->realisations();
        }
        // Contact
        else if (isset($get["route"]) && $get["route"] === "contact")
        {
            $fc->contact();
        }
        else if (isset($get["route"]) && $get["route"] === "contact-register") 
        {
            $fc->contactRegister();
        } 
        // Estimate
        else if (isset($get["route"]) && $get["route"] === "estimate") 
        {
            $fc->estimate();
        } 
        else if (isset($get["route"]) && $get["route"] === "estimate-register") 
        {
            $fc->estimateRegister();
        } 
        // Legals Mentions
        else if (isset($get["route"]) && $get["route"] === "mentions-legal") 
        {
            $dc->legalNotice();
        }
     
        //******************//
        //***   ADMIN   ***//
        //****************//
        
        // Acces
        else if (isset($get["route"]) && $get["route"] === "login") 
        {
            $atc->login();
        } 
        else if (isset($get["route"]) && $get["route"] === "check-login") 
        {
            $atc->Checklogin();
        }
        else if (isset($get["route"]) && $get["route"] === "logout") 
        {
            $atc->logout();
        }
        // dashboard 
        else if (isset($get["route"]) && $get["route"] === "dashboard") 
        {
            $ac->dashboard();
        }
        /*************************************************/
        // Service
        else if (isset($get["route"]) && $get["route"] === "services-list") 
        {
            $ac->servicesList();
        } 
        else if (isset($get["route"]) && $get["route"] === "show-service") 
        {
            $ac->showService($get["id"]);
        } 
        else if (isset($get["route"]) && $get["route"] === "create-service") 
        {
            $ac->createService();
        } 
        else if (isset($get["route"]) && $get["route"] === "update-service") 
        {
            $ac->updateService((int)$get["id"]);
        }
        else if (isset($get["route"]) && $get["route"] === "delete-service") 
        {
            $ac->deleteService((int)$get["id"]);
        }
        /*************************************************/
        // Realisation 
       
        else if (isset($get["route"]) && $get["route"] === "realisations-list") 
        {
            $ac->realisationsList();
        } 
        else if (isset($get["route"]) && $get["route"] === "create-realisation") 
        {
            $ac-> createRealisation();
        } 
        else if (isset($get["route"]) && $get["route"] === "show-realisation") 
        {
            $ac->showRealisation((int)$get["id"]);
        } 
        else if (isset($get["route"]) && $get["route"] === "update-realisation") 
        {
            $ac->updateRealisation((int)$get["id"]);
        }
        else if (isset($get["route"]) && $get["route"] === "delete-realisation") 
        {
            $ac->deleteRealisation((int)$get["id"]);
        }   
        /*************************************************/
        // Estimate
        else if (isset($get["route"]) && $get["route"] === "estimates-list") 
        {
            $ac->estimatesList();
        }
        else if (isset($get["route"]) && $get["route"] === "show-estimate") 
        {
            $ac->showEstimate((int)$get["id"]);
        } 
        else if (isset($get["route"]) && $get["route"] === "delete-estimate") 
        {
            $ac->deleteEstimate((int)$get["id"]);
        }
        else if (isset($get["route"]) && $get["route"] === "update-estimate") 
        {
            $ac->updateEstimate((int)$get["id"]);
        }
        /*************************************************/
        // contacts
        else if (isset($get["route"]) && $get["route"] === "contacts-list") 
        {
            $ac->contactsList();
        } 
        else if (isset($get["route"]) && $get["route"] === "show-contact") 
        {
            $ac->showContact((int)$get["id"]);
        } 
        else if (isset($get["route"]) && $get["route"] === "delete-contact") 
        {
            $ac->deleteContact((int)$get["id"]);
        }
        
        // Default
        else 
        {
            $dc->notFound();
        }
        
    }
}