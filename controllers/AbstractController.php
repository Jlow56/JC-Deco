<?php
  abstract class AbstractController
{
    private \Twig\Environment $twig;
    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader,[
            'debug' => true, 
            //  L'option est un répertoire de cache de compilation, où Twig met en cache les modèles compilés pour éviter la phase d'analyse des requêtes suivantes.
            // 'cache' => 'compilation_cache',
        ]);

        $twig->addExtension(new \Twig\Extension\DebugExtension());

        $this->twig = $twig;
    }

    protected function render(string $template, array $data) : void
    {
        echo $this->twig->render($template, $data);
    }
}

// abstract class AbstractController
// {
//     private \Twig\Environment $twig;
//     public function __construct()
//     {
//         $loader = new \Twig\Loader\FilesystemLoader('templates');
//         $twig = new \Twig\Environment($loader,[
//             'debug' => true,
//         ]);

//         $twig->addExtension(new \Twig\Extension\DebugExtension());
//         $twig->addGlobal('sessionToken', $_SESSION["csrf-token"]);
//         $twig->addGlobal('url', $_SERVER['REQUEST_URI']);
//         $uri = $_SERVER['REQUEST_URI'];
//         $segments = explode('/', $uri);
//         $route = end($segments);
//         $twig->addGlobal('current_route', $route);

//         $this->twig = $twig;
//     }

//     protected function render(string $template, array $data) : void
//     {
//         echo $this->twig->render($template, $data);
//     }

//     protected function renderJson(array $data) : void
//     {
//     echo json_encode($data);
//     }
// }
