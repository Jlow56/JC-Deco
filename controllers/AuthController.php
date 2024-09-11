<?php

class AuthController extends AbstractController 
{
    public function __construct()
    {

    }

    public function login() : void
    {
       
        $this->render('admin\login.html.twig',[
            "login" => $login,
        ]);
    }
    
    public function checkLogin() : void
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") 
        {
            if(isset($_POST["email"]) || !filter_var($getData['email'], FILTER_VALIDATE_EMAIL)&&  isset($_POST["password"]))
            {
                $am = new AdminManger();
                $admin = $am->findOne($_POST["email"]);

                if($admin->getId() !== null)
                {
                    if(password_verify($_POST["password"], $admin->getPassword()))
                    {
                        $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
                        $_SESSION["admin"] = $admin;
                        $this->render('admin\admin-page.html.twig');
                    }
                    else
                    {
                        $this->render('admin\login.html.twig');
                    }
                }
                else 
                {
                    $this->render('admin\login.html.twig');
                }
            }
            else 
            {
                $this->render('admin\login.html.twig');
            }
        }
    }
    
    public function logout() : void
    {
        session_destroy();
        $this->render('admin\login.html.twig');
    }
}

