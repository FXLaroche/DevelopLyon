<?php

namespace App\Controller;

use App\Model\SearchManager;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

abstract class AbstractController
{
    protected Environment $twig;
    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        $loader = new FilesystemLoader(APP_VIEW_PATH);
        $this->twig = new Environment(
            $loader,
            [
                'cache' => false,
                'debug' => (ENV === 'dev'),
            ]
        );
        $this->twig->addExtension(new DebugExtension());
    }

    public function twigRender(string $template, array $params): string
    {
        $searchManager = new SearchManager();
        $searchs = $searchManager->selectAll();
        $params['searchs'] = $searchs;
        if (isset($_SESSION['nickname'])) {
            $params['nickname'] = $_SESSION['nickname'];
            $params['connectionOption'] = "Se déconnecter";
            $params['connectionLink']  = "logout";
        } else {
            $params['nickname'] = "";
            $params['connectionOption'] = "Se connecter";
            $params['connectionLink']  = "login";
        }
        return $this->twig->render($template, $params);
    }
}
