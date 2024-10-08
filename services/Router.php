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


        if (!isset($get["route"])) {
            $dc->home();
        } else if (isset($get["route"]) && $get["route"] === "home") {
            $dc->home();
        } else if (isset($get["route"]) && $get["route"] === "services") {
            $sc->services();
        } else if (isset($get["route"]) && $get["route"] === "realisations") {
            $rc->realisations();
        } else if (isset($get["route"]) && $get["route"] === "contact") {
            $fc->contact();
        } else if (isset($get["route"]) && $get["route"] === "estimate") {
            $fc->estimate();
        } else if (isset($get["route"]) && $get["route"] === "login") {
            $atc->login();
        } else if (isset($get["route"]) && $get["route"] === "dashboard") {
            $ac->dashboard();
        } else {
            $dc->notFound();
        }
    }
}