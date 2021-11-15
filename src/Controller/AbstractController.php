<?php

namespace App\Controller;

use App\Model\CategoryManager;
use App\Model\PostManager;
use App\Model\SearchManager;
use App\Model\ThemeManager;
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

    public function urlContains($expr)
    {
        $regex = '/\/' . $expr . '/';
        $url = $_SERVER['REQUEST_URI'];
        if (preg_match($regex, $url) === 1) {
            return true;
        } elseif (preg_match($regex, $url) === 0) {
            return false;
        }
    }

    public function getPostData(): array
    {
        $postData = [];
        $postManager = new PostManager();
        if ($this->urlContains('post\/') && !$this->urlContains('add')) {
            $postId = (int)trim($_GET['id']);
            $postData = $postManager->selectPostTreeData($postId);
        }
        return $postData;
    }

    public function categoryId(array $postData, string $arrayIndex): ?int
    {
        if (isset($postData[$arrayIndex])) {
            return $postData[$arrayIndex];
        }
        if (isset($_GET['theme'])) {
            return (int)trim($_GET['theme']);
        }
        return null;
    }
    public function getThemeList(?int $categoryId): array
    {
        $themeList = [];
        if (!is_null($categoryId)) {
            $themeManager = new ThemeManager();
            $themeList = $themeManager->selectThemesByCategoryId($categoryId);
        }
        return $themeList;
    }

    public function fillParams(array &$paramName, array $data): void
    {
        foreach ($data as $dataName => $dataValue) {
            $paramName[$dataName] = $dataValue;
        }
    }

    public function getCategoryList(): array
    {
        if ($_SERVER['REQUEST_URI'] !== "/") {
            $categoryManager = new CategoryManager();
            return $categoryManager->selectAll();
        }
        return [];
    }

    public function twigRender(string $template, array $params): string
    {
        $searchManager = new SearchManager();
        $searchs = $searchManager->selectAll();
        $params['searchs'] = $searchs;

        $params['postData'] = [];
        $params['themeList'] = [];
        $params['categoryList'] = [];

        $this->fillParams($params['postData'], $this->getPostData());

        $categoryId = $this->categoryId($params['postData'], 'category_id');
        $this->fillParams($params['themeList'], $this->getThemeList($categoryId));

        $this->fillParams($params['categoryList'], $this->getCategoryList());

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
